<?php

namespace Fuel\Migrations;

class Create_parts
{
	public function up()
	{
		\DBUtil::create_table('parts', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'status' => array('constraint' => 3, 'type' => 'int'),
			'price' => array('constraint' => 5, 'type' => 'int'),
			'ship_number' => array('constraint' => 5, 'type' => 'int', 'null' => true),
			'box_number' => array('constraint' => 3, 'type' => 'int', 'null' => true),
			'tracking' => array('constraint' => 15, 'type' => 'varchar', 'null' => true),
			'memo' => array('constraint' => 60, 'type' => 'varchar', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('parts');
	}
}