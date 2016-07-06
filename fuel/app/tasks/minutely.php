<?php

namespace Fuel\Tasks;

use Yahoo\Dropbox as Dbx;
use Yahoo\Browser as Browser;
use Fuel\Core\Log as Log;
use Fuel\Core\File as File;
use Fuel\Core\Date as Date;
use Fuel\Core\Input as Input;
use Fuel\Core\Cache as Cache;

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
			$auc_ids = [];
			$select = \DB::select('auc_id')->from('auctions')->order_by('id','desc')->limit(\Config::get('my.task.last_won_limit'))->execute()->as_array();
			$user_id = \DB::select('id')->from('users')->where('username', \Config::get('my.main_bidder'))->execute()->as_array();

			foreach ($select as $value) {
				$auc_ids[] = $value['auc_id'];
			}
			
			$val = \Model_Auction::validate();

			try
			{
				$browser = new \Browser();

				foreach ($browser->won(self::$PAGE_TO_UPDATE) as $auc_id) {
					
					if ( !in_array($auc_id, $auc_ids) ){

						try
						{
							$auc_xml = $browser->getXmlObject($auc_id);

							$auc_values = [];
							$auc_values['auc_id'] = (string) $auc_xml->Result->AuctionID;
							$auc_values['title'] = (string) $auc_xml->Result->Title;
							$auc_values['price'] = (int) $auc_xml->Result->Price;
							$auc_values['won_date'] = \Date::create_from_string( (string) $auc_xml->Result->EndTime , 'yahoo_date')->format('mysql');
							$auc_values['user_id'] = $user_id[0]['id'];

							$vendor_name = (string) $auc_xml->Result->Seller->Id;
							$vendor_id = \DB::select('id')->from('vendors')->where('name', '=', $vendor_name)->execute()->as_array();
							
							if ( !empty($vendor_id) )
							{
								$auc_values['vendor_id'] = $vendor_id[0]['id'];
							}
							else
							{
								if ( \Model_Vendor::forge()->set(['name' => $vendor_name, 'by_now' => 0])->save() )
								{
									$vendor_id = \DB::select('id')->from('vendors')->where('name', '=', $vendor_name)->execute()->as_array();
									$auc_values['vendor_id'] = $vendor_id[0]['id'];
								}
							}
							
							if ( $val->run($auc_values) )
							{
								\Model_Auction::forge()->set($auc_values)->save();
							}
							else
							{
								foreach ($val->error() as $value) {
									\Log::error('Validation error in task Minutely on method check_won_at_time : '.$value);
								}
							}
						}
						catch (\BrowserException $e)
						{
							\Log::error("ID: ".$auc_id." Error: ".$e->getMessage());
						}
					}
				}
			}
			catch (\BrowserLoginException $e)
			{
				\Log::error("Login error: ".$e->getMessage());
			}
			catch (\ParserException $e)
			{
				\Log::error("Parser error: ".$e->getMessage());
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
		$host = \Config::get('db.default.connection.hostname');
		$port = \Config::get('db.default.connection.port');
		$user = \Config::get('db.default.connection.username');
		$pass = \Config::get('db.default.connection.password');
		$db   = \Config::get('db.default.connection.database');

		$name = \Date::forge(time())->format('db_backup') . '.sql.gz';
		$path = APPPATH.'/tmp/'. $name;

		exec("mysqldump --user={$user} --password={$pass} --host={$host} --port={$port} {$db} | gzip > {$path}");
		Dbx::save_to_dbx($path, \Config::get('my.dropbox.db_path')."/{$name}");

		if ( \File::exists($path) )
		{
			 \File::delete($path);
		}
	}
}
