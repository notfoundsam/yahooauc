<?php

namespace Fuel\Tasks;

/**
* Convert old DB
* Don't run if you have BD created through migration
*/
class Db_convert
{
	public static function run()
	{

		//Drop tables is exists
		if(\DBUtil::table_exists('auctions'))
		{
			\DBUtil::drop_table('auctions');	
		}
		if(\DBUtil::table_exists('parts'))
		{
			\DBUtil::drop_table('parts');	
		}
		if(\DBUtil::table_exists('balances'))
		{
			\DBUtil::drop_table('balances');	
		}
		if(\DBUtil::table_exists('bidlogs'))
		{
			\DBUtil::drop_table('bidlogs');	
		}
		if(\DBUtil::table_exists('ships'))
		{
			\DBUtil::drop_table('ships');	
		}

		//Drop foreign key in imported db
		\DBUtil::drop_foreign_key('auction', 'auction_part');

		//Drop index in imported db
		\DBUtil::drop_index('auction', 'auction_part');

		//Rename tables
		\DBUtil::rename_table('auction', 'auctions');
		\DBUtil::rename_table('part', 'parts');
		\DBUtil::rename_table('balance', 'balances');
		\DBUtil::rename_table('bidslog', 'bidlogs');
		\DBUtil::rename_table('ship', 'ships');

		// Modify and add fields in table auctions
		\DBUtil::modify_fields('auctions', [
				'auctionID'   => ['constraint' => 10, 'type' => 'varchar', 'name' => 'auc_id'],
				'description' => ['constraint' => 80, 'type' => 'varchar', 'name' => 'title'],
				'groupId'     => ['constraint' => 10, 'type' => 'int', 'name' => 'part_id', 'null' => true],
				'itemCount'   => ['constraint' => 3, 'type' => 'int', 'name' => 'item_count'],
				'wonDate'     => ['type' => 'datetime', 'name' => 'won_date'],
				'vendor'      => ['constraint' => 40, 'type' => 'varchar', 'name' => 'vendor_id'],
				'memo'        => ['constraint' => 60, 'type' => 'varchar', 'null' => true],
				'wonUser'     => ['constraint' => 20, 'type' => 'varchar', 'name' => 'won_user', 'null' => true],
			]
		);
		\DBUtil::add_fields('auctions', [
				'created_at'  => ['constraint' => 11, 'type' => 'int', 'null' => true],
				'updated_at'  => ['constraint' => 11, 'type' => 'int', 'null' => true],
			]
		);

		// Modify and add fields in table parts
		\DBUtil::modify_fields('parts', [
				'groupId'     => ['constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true, 'name' => 'id'],
				'boxNumber'   => ['constraint' => 3, 'type' => 'int', 'name' => 'box_number', 'null' => true],
				'partPrice'   => ['constraint' => 5, 'type' => 'int', 'name' => 'price'],
				'partStatus'  => ['constraint' => 3, 'type' => 'int', 'name' => 'status'],
				'shipNumber'  => ['constraint' => 5, 'type' => 'int', 'name' => 'ship_number', 'null' => true],
				'trackNumber' => ['constraint' => 15, 'type' => 'varchar', 'name' => 'tracking', 'null' => true],
				'memo'        => ['constraint' => 60, 'type' => 'varchar', 'null' => true],
			]
		);
		\DBUtil::add_fields('parts', [
				'created_at'  => ['constraint' => 11, 'type' => 'int', 'null' => true],
				'updated_at'  => ['constraint' => 11, 'type' => 'int', 'null' => true],
			]
		);

		// Modify and add fields in table vendors
		\DBUtil::modify_fields('vendors', [
				'vendor'      => ['constraint' => 40, 'type' => 'varchar', 'name' => 'name'],
				'byNow'       => ['constraint' => 1, 'type' => 'int', 'name' => 'by_now'],
				'postIndex'   => ['constraint' => 20, 'type' => 'varchar', 'name' => 'post_index', 'null' => true],
				'address'     => ['constraint' => 80, 'type' => 'varchar', 'null' => true],
				'color'       => ['constraint' => 10, 'type' => 'varchar', 'null' => true],
				'memo'        => ['constraint' => 200, 'type' => 'varchar', 'null' => true],
			]
		);
		\DBUtil::add_fields('vendors', [
				'created_at'  => ['constraint' => 11, 'type' => 'int', 'null' => true],
				'updated_at'  => ['constraint' => 11, 'type' => 'int', 'null' => true],
			]
		);

		// Replace vendor name to vendor id in auctions and add vendor if not exists
		try {
			
			$auctions = \DB::select_array(['id', 'vendor_id'])->from('auctions')->execute();

			\DB::start_transaction();

			foreach ($auctions as $auction) {

				$vendor = \DB::select('id')->from('vendors')->where('name', '=', $auction['vendor_id'])->execute()->as_array();

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

			// Modify field type from varchat to int in auctions
			\DBUtil::modify_fields('auctions', [
				'vendor_id' => ['constraint' => 11, 'type' => 'int'],
			]);

			\DB::commit_transaction();
			
		} catch (Exception $e)
		{
			\DB::rollback_transaction();
			print "Replace vendor name to vendor id in auctions and add vendor if not exists was filed\n";
		}

		// Replace won_user to user_id in auctions
		try
		{
			$users = \DB::select_array(['id', 'username'])->from('users')->execute();

			\DB::start_transaction();

			foreach ($users as $user)
			{
				\DB::update('auctions')->value('won_user', $user['id'])->where('won_user', '=', $user['username'])->execute();
			}

			\DB::commit_transaction();

			// Modify field type from varchat to int in auctions
			\DBUtil::modify_fields('auctions', [
				'won_user' => ['constraint' => 11, 'type' => 'int', 'name' => 'user_id'],
			]);
		}
		catch (Exception $e)
		{
			\DB::rollback_transaction();
			print "Replace won_user to user_id in auctions was filed\n";
		}

		// Add index to auctions and parts
		\DBUtil::create_index('auctions', 'part_id', 'part_id');
		\DBUtil::create_index('parts', 'status', 'status');
		\DBUtil::create_index('vendors', 'name', 'name');

		// delete auc_id g143869725 !!!!
		print "Data base successfully converted\n";
	}
}
