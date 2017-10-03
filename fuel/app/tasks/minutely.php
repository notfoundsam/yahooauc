<?php

namespace Fuel\Tasks;

use Yahoo\Dropbox as Dbx;
use Fuel\Core\Log as Log;
use Fuel\Core\File as File;
use Fuel\Core\Date as Date;
use Fuel\Core\Input as Input;
use Fuel\Core\Cache as Cache;
use Yahooauc\Browser as Browser;
use Yahooauc\Exceptions\BrowserLoginException as BrowserLoginException;
use Yahooauc\Exceptions\BrowserException as BrowserException;
use Yahooauc\Exceptions\ParserException as ParserException;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

/**
* 
*/
class Minutely
{
	private static $LAST_CHECK_TIME = 0;
	private static $PAGE_TO_UPDATE  = 1;

	public static function run()
	{
		try
		{
			self::$LAST_CHECK_TIME = \Cache::get('yahoo.won_last_check');
		}
		catch (\CacheNotFoundException $e)
		{
			\Cache::set('yahoo.won_last_check', time());
		}

		self::db_backup_at_time();
		self::check_won_at_time();
	}

	private static function check_won_at_time()
	{

		$interval = \Config::get('my.task.lot_update_interval');

        if ( self::$LAST_CHECK_TIME < strtotime("-{$interval} minute") )
		{

            $userName = \Config::get('my.yahoo.user_name');
            $userPass = \Config::get('my.yahoo.user_pass');
            $appId    = \Config::get('my.yahoo.user_appid');

            try
            {
                $cookieJar = \Cache::get('yahoo.cookies');
            }
            catch (\CacheNotFoundException $e)
            {
                $cookieJar = null;
            }

			$auc_ids = [];

    		$select = \DB::select('auc_id')->from('auctions')->order_by('id','desc')->limit(\Config::get('my.task.last_won_limit'))->execute()->as_array();
            $user_id = \DB::select('id')->from('users')->where('username', \Config::get('my.main_bidder'))->execute()->as_array();

            foreach ($select as $value) {
                $auc_ids[] = $value['auc_id'];
            }
            
            $val = \Model_Auction::validate();
            
            try
            {
                $browser = new Browser($userName, $userPass, $appId, $cookieJar, \Config::get('my.rmccue'));

                foreach ($browser->getWonIds() as $auc_id) {
                    
                    if ( !in_array($auc_id, $auc_ids) )
                    {
                        try
                        {
                            $auc_xml = $browser->getAuctionInfoAsXml($auc_id);

                            $auc_values = [
                                'auc_id'   => (string) $auc_xml->Result->AuctionID,
                                'title'    => (string) $auc_xml->Result->Title,
                                'price'    => isset($auc_xml->Result->TaxinPrice) ? (int) $auc_xml->Result->TaxinPrice : (int) $auc_xml->Result->Price,
                                'won_date' => \Date::create_from_string( (string) $auc_xml->Result->EndTime, 'yahoo_date')->format('mysql'),
                                'user_id'  => $user_id[0]['id']
                            ];

                            $vendor_name = (string) $auc_xml->Result->Seller->Id;
                            $vendor_id = \DB::select('id')->from('vendors')->where('name', '=', $vendor_name)->execute()->as_array();
                            
                            if ( !empty($vendor_id) )
                            {
                                $auc_values['vendor_id'] = $vendor_id[0]['id'];
                            }
                            else
                            {
                                $v = \Model_Vendor::forge()->set(['name' => $vendor_name, 'by_now' => 0]);

                                if ($v->save())
                                {
                                    $auc_values['vendor_id'] = $v->id;
                                }
                            }
                            
                            if ( $val->run($auc_values) )
                            {
                                \Model_Auction::forge()->set($auc_values)->save();
                            }
                            else
                            {
                                foreach ($val->error() as $value)
                                {
                                    \Log::error('Validation error in task Minutely on method check_won_at_time : '.$value);
                                }

                                \Log::error("Could not save auction ".$auc_values['auc_id']);
                            }
                        }
                        catch (BrowserException $e)
                        {
                            \Log::error("ID: ".$auc_id." Error: ".$e->getMessage());
                        }
                    }
                }

                $cookieJar = $browser->getCookie();
                \Cache::set('yahoo.cookies', $cookieJar, \Config::get('my.yahoo.cookie_exp'));
            }
            catch (BrowserLoginException $e)
            {
                \Log::error("ID: ".$auc_id." Error: ".$e->getMessage());
            }
            catch (ParserException $e)
            {
                \Log::error("ID: ".$auc_id." Error: ".$e->getMessage());
            }

			\Cache::set('yahoo.won_last_check', time());
		}

        self::save_image_from_cache();
	}

	private static function db_backup_at_time()
	{
		if ( \Date::forge(time())->format('db_task') == \Config::get('my.task.backup_time') )
		{
			self::db_backup();
		}
	}

	public static function db_backup()
	{
		$host = getenv('RDS_HOSTNAME');
		$port = getenv('RDS_PORT');
		$user = getenv('RDS_USERNAME');
		$pass = getenv('RDS_PASSWORD');
		$db   = getenv('RDS_DB_NAME');

		$name = \Fuel::$env . "_" . \Date::forge(time())->format('db_backup') . '.sql.gz';
		$path = APPPATH.'/tmp/' . $name;

		exec("mysqldump --user={$user} --password={$pass} --host={$host} --port={$port} {$db} | gzip > {$path}");
		Dbx::save_to_dbx($path, \Config::get('my.dropbox.db_path')."/{$name}");

		if ( \File::exists($path) )
		{
			 \File::delete($path);
		}
	}

	public static function save_image_from_cache()
    {
        $userName = \Config::get('my.yahoo.user_name');
        $userPass = \Config::get('my.yahoo.user_pass');
        $appId    = \Config::get('my.yahoo.user_appid');

        $s3 = \Helper::getS3Client();

        try
        {
            $cookieJar = \Cache::get('yahoo.cookies');
        }
        catch (\CacheNotFoundException $e)
        {
            $cookieJar = null;
        }

        try
        {
            $browser = new Browser($userName, $userPass, $appId, $cookieJar, \Config::get('my.rmccue'));
        }
        catch (BrowserLoginException $e)
        {
            \Log::error("Error: ".$e->getMessage());

            return;
        }

        $items = \Model_Auction::find('all', [
            'where' => [
                's3_img' => 0
            ]
        ]);

        foreach ($items as $i)
        {
            try
            {
                $images = \Cache::get('yahoo.images.' . $i->auc_id);
            }    
            catch (\CacheNotFoundException $e)
            {
                \Log::warning("ID: {$i->auc_id} Warning: cache not found");
                $images = $browser->getAuctionImgsUrl($i->auc_id);
            }

            try
            {
                foreach ($images as $index => $url)
                {
                    $request = \Request::forge($url, 'curl')->execute();
                    $type = $request->response_info('content_type');

                    $path = self::create_s3_path($i->id, $type, $index);

                    if ($path === false)
                    {
                        \Log::error("ID: {$i->auc_id} Error: unknown image type");
                        continue;
                    }

                    $result = $s3->putObject([
                        'Bucket'       => \Config::get('my.aws.bucket'),
                        'Key'          => $path,
                        'Body'         => $request->response()->body(),
                        'ContentType'  => $type,
                        'ACL'          => 'public-read',
                        'CacheControl' => 'max-age=2678400',
                    ]);
                }
            }
            catch (S3Exception $e)
            {
                \Log::error("ID: {$i->auc_id} S3 Error: could not put image to bucket");
            }
            catch (\Exception $e)
            {
                \Log::error("ID: {$i->auc_id} Error: could not get image from {$url}");
            }

            \DB::start_transaction();
            
            try
            {
                $i->s3_img = 1;
                $i->save();
                \DB::commit_transaction();
            }
            catch (\Exception $e)
            {
                \Log::error($e);
                \DB::rollback_transaction();
            }
        }
	}

    private static function create_s3_path($id, $type, $index)
    {

        switch ($type)
        {
            case 'image/png':
            case 'image/x-png':
                $extension = 'png';
                break;
            case 'image/jpeg' :
                $extension = 'jpg';
                break;
            case 'image/gif':
                $extension = 'gif';
                break;
            default:
                return false;
        }

        return $id . "/{$index}_" . \Str::random('unique') . '.' . $extension;
    }
}
