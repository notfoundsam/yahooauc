<?php
class Model_Bid extends \Orm\Model
{
    protected static $_table_name = 'bid_histories';

    protected static $_properties = array(
        'id',
        'username',
        'ip',
        'auc_id',
        'price',
        'created_at',
    );

    protected static $_conditions = [
        'order_by' => [
            'id' => 'desc'
        ],
    ];

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
        ),
    );
}
