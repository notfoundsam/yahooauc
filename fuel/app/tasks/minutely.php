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
		// self::save_image_from_cache();
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
                $browser = new Browser($userName, $userPass, $appId, $cookieJar);

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
		$host = getenv('OPENSHIFT_MYSQL_DB_HOST');
		$port = getenv('OPENSHIFT_MYSQL_DB_PORT');
		$user = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
		$pass = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
		$db   = 'htmlunit';

		$name = \Date::forge(time())->format('db_backup') . '.sql.gz';
		$path = APPPATH.'/tmp/'. $name;

		exec("mysqldump --user={$user} --password={$pass} --host={$host} --port={$port} {$db} | gzip > {$path}");
		Dbx::save_to_dbx($path, \Config::get('my.dropbox.db_path')."/{$name}");

		if ( \File::exists($path) )
		{
			 \File::delete($path);
		}
	}

	private static function save_image_from_cache()
	{
		
	}
}
