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
			
			$auctions = \DB::select_array(['id', 'vendor_id'])->from('auctions')->execute();

			\DB::start_transaction();

			foreach ($auctions as $auction) {

				$vendor = \DB::select('id')->from('vendors')->where('name', '=', $auction['vendor_id'])->execute()->as_array();
				\Log::debug('id');
				\Log::debug($vendor[0]['id']);

				if (!empty($vendor))
				{
					\DB::update('auctions')->value("vendor_id", $vendor[0]['id'])->where('id', '=', $auction['id'])->execute();
				}
				else
				{
					$result = \DB::insert('vendors')->set(['name' => $auction['vendor_id'],])->execute();
					\DB::update('auctions')->value("vendor_id", $result[0])->where('id', '=', $auction['id'])->execute();
				}
			}

			\DB::commit_transaction();
			
		} catch (Exception $e) {
			
			\DB::rollback_transaction();

		}
		
	}
}