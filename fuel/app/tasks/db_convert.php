<?php

namespace Fuel\Tasks;

/**
* 
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
				'auctionID' => ['constraint' => 10, 'type' => 'varchar', 'name' => 'auc_id'],
				'description' => ['constraint' => 80, 'type' => 'varchar'],
				'groupId' => ['constraint' => 10, 'type' => 'int', 'name' => 'part_id', 'null' => true],
				'itemCount' => ['constraint' => 3, 'type' => 'int', 'name' => 'item_count'],
				'wonDate' => ['type' => 'datetime', 'name' => 'won_date', 'null' => true],
				'vendor' => ['constraint' => 40, 'type' => 'varchar'],
				'memo' => ['constraint' => 60, 'type' => 'varchar', 'null' => true],
				'wonUser' => ['constraint' => 20, 'type' => 'varchar', 'name' => 'won_user', 'null' => true],
			]
		);
		\DBUtil::add_fields('auctions', [
				'created_at' => ['constraint' => 11, 'type' => 'int', 'null' => true],
				'updated_at' => ['constraint' => 11, 'type' => 'int', 'null' => true],
			]
		);

		// Modify and add fields in table parts
		\DBUtil::modify_fields('parts', [
				'groupId' => ['constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true, 'name' => 'id'],
				'boxNumber' => ['constraint' => 3, 'type' => 'int', 'name' => 'box_number', 'null' => true],
				'partPrice' => ['constraint' => 5, 'type' => 'int', 'name' => 'price'],
				'partStatus' => ['constraint' => 3, 'type' => 'int', 'name' => 'status'],
				'shipNumber' => ['constraint' => 5, 'type' => 'int', 'name' => 'ship_number', 'null' => true],
				'trackNumber' => ['constraint' => 15, 'type' => 'varchar', 'name' => 'tracking', 'null' => true],
				'memo' => ['constraint' => 60, 'type' => 'varchar', 'null' => true],
			]
		);
		\DBUtil::add_fields('parts', [
				'created_at' => ['constraint' => 11, 'type' => 'int', 'null' => true],
				'updated_at' => ['constraint' => 11, 'type' => 'int', 'null' => true],
			]
		);

		// Add index to auctions and parts
		\DBUtil::create_index('auctions', 'part_id', 'part_id');
		\DBUtil::create_index('parts', 'status', 'status');
	}
}
