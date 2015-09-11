<?php
class Model_Part extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'status',
		'price',
		'ship_number',
		'box_number',
		'tracking',
		'memo',
		'created_at',
		'updated_at',
	);

	protected static $_has_many = ['auctions'];

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
		$val->add_field('status', 'Status', 'required|valid_string[numeric]');
		$val->add_field('price', 'Price', 'required|valid_string[numeric]');
		$val->add_field('ship_number', 'Ship Number', 'required|valid_string[numeric]');
		$val->add_field('box_number', 'Box Number', 'required|valid_string[numeric]');
		$val->add_field('tracking', 'Tracking', 'required|max_length[15]');
		$val->add_field('memo', 'Memo', 'required|max_length[60]');

		return $val;
	}

}
