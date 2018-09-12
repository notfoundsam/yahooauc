<?php

namespace Fuel\Migrations;

class Add_login_histories
{
  public function up()
  {
    \DBUtil::create_table('login_histories', [
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
      'result' => [
        'constraint' => 20,
        'type' => 'varchar',
        'null' => true,
        'default' => null,
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
    \DBUtil::drop_table('login_histories');
  }
}
