<?php

/**
 * 
 */
class Arrlog
{
	/**
	 * [arr_to_log description]
	 * @param  [type] $arr  [description]
	 * @param  string $tabs [description]
	 * @return void
	 */
	public static function arr_to_log($arr, $tabs = "\t")
	{
		foreach ($arr as $k => $v)
		{
			if (is_array($v))
			{
				Log::debug($tabs.$k." => [");
				self::arr_to_log($v, $tabs.$tabs);
				Log::debug($tabs."]");
			}
			else
			{
				Log::debug($tabs.$k." => ".$v);
			}
		}
	}
}
