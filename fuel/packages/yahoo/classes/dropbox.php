<?php

namespace Yahoo;

use \Dropbox as Dbx;
use \Fuel\Core\File as File;
use \Fuel\Core\Log as Log;

/**
* 
*/
class Dropbox
{
	/**
	 * [save_to_dbx description]
	 * @param  [type] $from [description]
	 * @param  [type] $to   [description]
	 * @return [type]       [description]
	 */
	public static function save_to_dbx($from, $to)
	{
		if ( $from && $to && \File::exists($from) )
		{
			try
			{
				$dbx_client = new Dbx\Client(\Config::get('my.dropbox.token'), "PHP-Example/1.0");
				$f = fopen($from, "rb");
				$result = $dbx_client->uploadFile($to, Dbx\WriteMode::add(), $f);
				fclose($f);
			}
			catch (\Exception $e)
			{
				print $e."\n";
				\Log::error($e);
			}
		}
	}
}