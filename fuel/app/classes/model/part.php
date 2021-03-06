<?php
class Model_Part extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'status' => [
			'default' => 0,
		],
		'price' => [
			'default' => 0,
		],
		'ship_number',
		'box_number' => [
			'default' => 0,
		],
		'tracking',
		'memo',
		'created_at',
		'updated_at',
	);

	protected static $_has_many = [
		'auctions' => [
			'conditions' => [
				'order_by' => [
					'won_date' => 'DESC'
				],
			],
		],
	];

	protected static $_conditions = [
		'order_by' => [
			'id' => 'DESC'
		],
	];

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('status', 'Status', 'required|valid_string[numeric]|max_length[3]');
		$val->add_field('price', 'Price', 'required|valid_string[numeric]|max_length[5]');
		$val->add_field('box_number', 'Box Number', 'valid_string[numeric]|max_length[3]');
		$val->add_field('tracking', 'Tracking', 'max_length[15]');
		$val->add_field('memo', 'Memo', 'max_length[60]');

		return $val;
	}

}
