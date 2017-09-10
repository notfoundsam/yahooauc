<?php

namespace Fuel\Migrations;

class Add_s3_img_to_auctions
{
    public function up()
    {
        \DBUtil::add_fields('auctions', array(
            's3_img' => array(
                'constraint' => 1,
                'type' => 'tinyint',
                'default' => 1,
                'after' => 'memo'
            )
        ));
    }

    public function down()
    {
        \DBUtil::drop_fields('auctions', 's3_img');
    }
}
