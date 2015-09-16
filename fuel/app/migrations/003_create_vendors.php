<?php

namespace Fuel\Migrations;

class Create_vendors
{
	public function up()
	{
		\DBUtil::create_table('vendors', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 40, 'type' => 'varchar'),
			'by_now' => array('constraint' => 1, 'type' => 'int'),
			'post_index' => array('constraint' => 20, 'type' => 'varchar', 'null' => true),
			'address' => array('constraint' => 80, 'type' => 'varchar', 'null' => true),
			'color' => array('constraint' => 10, 'type' => 'varchar', 'null' => true),
			'memo' => array('constraint' => 200, 'type' => 'varchar', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('vendors');
	}
}