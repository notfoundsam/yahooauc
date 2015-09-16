<?php

namespace Fuel\Tasks;

/**
* 
*/
class Sql
{
	public static function run()
	{
		try {
			
			\DB::start_transaction();

			$vendors = \Model_Vendor::find('all');

			foreach ($vendors as $vendor) {
				\DB::update('auctions')->value("vendor_id", $vendor->id)->where('vendor_id', '=', $vendor->name)->execute();
			}

			\DB::commit_transaction();
			
		} catch (Exception $e) {
			
			\DB::rollback_transaction();

		}
		
	}
}