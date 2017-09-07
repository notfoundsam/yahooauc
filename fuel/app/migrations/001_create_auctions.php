<?php

namespace Fuel\Migrations;

class Create_auctions
{
	public function up()
	{
		\DBUtil::create_table('auctions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'item_count' => array('constraint' => 3, 'type' => 'int'),
			'auc_id' => array('constraint' => 10, 'type' => 'varchar'),
			'title' => array('constraint' => 200, 'type' => 'varchar'),
			'price' => array('constraint' => 5, 'type' => 'int'),
			'won_date' => array('type' => 'datetime'),
			'vendor_id' => array('constraint' => 11, 'type' => 'int'),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'part_id' => array('constraint' => 10, 'type' => 'int', 'null' => true),
			'memo' => array('constraint' => 60, 'type' => 'varchar', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('auctions');
	}
}