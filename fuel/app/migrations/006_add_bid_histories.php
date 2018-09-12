<?php

namespace Fuel\Migrations;

class Add_bid_histories
{
  public function up()
  {
    \DBUtil::create_table('bid_histories', [
      'id' => [
        'constraint' => 11,
        'type' => 'int',
        'auto_increment' => true,
        'unsigned' => true
      ],
      'username' => [
        'constraint' => 255,
        'type' => 'varchar'
      ],
      'ip' => [
        'constraint' => 255,
        'type' => 'varchar',
        'null' => true,
        'default' => null,
      ],
      'auc_id' => [
        'constraint' => 10,
        'type' => 'varchar',
        'null' => true,
        'default' => null,
      ],
      'price' => [
        'constraint' => 5,
        'type' => 'int',
        'null' => false,
      ],
       'created_at' => [
        'type' => 'datetime',
        'null' => true,
        'default' => null,
      ],
    ], ['id']);
  }

  public function down()
  {
    \DBUtil::drop_table('bid_histories');
  }
}
