<?php

namespace Fuel\Tasks;
use Fuel\Core\Log as Log;

/**
* 
*/
class Cron
{
	public static function run()
	{
		Log::error('test'.time());
	}
}