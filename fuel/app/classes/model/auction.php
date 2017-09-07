<?php
class Model_Auction extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'item_count' => [
			'default' => 1,
		],
		'auc_id',
		'title',
		'price',
		'won_date',
		'vendor_id',
		'user_id',
		'part_id',
		'memo',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = [
		'part',
		'vendor',
		'user' => [
			'model_to' => 'Model\\Auth_User',
			'key_from' => 'user_id',
			'key_to' => 'id',
		]
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

	public static function validate($factory = 'default')
	{
		$val = Validation::forge($factory);
		// $val->add_field('item_count', 'Item Count', 'required|valid_string[numeric]|max_length[3]');
		$val->add_field('auc_id', 'Auc Id', 'required|trim|max_length[10]');
		$val->add_field('title', 'Title', 'required|max_length[200]');
		$val->add_field('price', 'Price', 'required|trim|valid_string[numeric]|max_length[5]');
		$val->add_field('won_date', 'Won Date', 'required|valid_date');
		$val->add_field('vendor_id', 'Vendor Id', 'required|valid_string[numeric]');
		$val->add_field('user_id', 'User Id', 'required|valid_string[numeric]');
		$val->add_field('memo', 'Memo', 'max_length[60]');

		return $val;
	}

	public static function validate_edit($factory = 'default')
	{
		$val = Validation::forge($factory);
		$val->add_field('item_count', 'Item Count', 'required|valid_string[numeric]|max_length[3]');
		$val->add_field('price', 'Price', 'required|valid_string[numeric]|max_length[5]');
		$val->add_field('memo', 'Memo', 'max_length[60]');

		return $val;
	}
}
