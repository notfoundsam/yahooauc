<?php

namespace Fuel\Migrations;

class Create_yahoo
{
	public function up()
	{
		\DBUtil::create_table('yahoo', [
			'id' => [
				'constraint' => 11,
				'type' => 'int',
				'auto_increment' => true,
			],
			'userid' => [
				'constraint' => 50,
				'type' => 'varchar'
			],
			'cookies' => [
				'type' => 'text',
				'null' => true
			],
			'updated_at' => [
				'constraint' => 11,
				'type' => 'int',
				'default' => time()
			],

		],[
			'id'
		]);
	}

	public function down()
	{
		\DBUtil::drop_table('yahoo');
	}
}