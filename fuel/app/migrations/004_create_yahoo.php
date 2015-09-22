<?php

namespace Fuel\Migrations;

class Create_yahoo
{
	public function up()
	{
		\DBUtil::create_table('yahoo', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'userid' => array('constraint' => 50, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('yahoo');
	}
}