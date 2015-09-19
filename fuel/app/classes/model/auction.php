<?php
class Model_Auction extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'item_count',
		'auc_id',
		'description',
		'price',
		'won_date',
		'vendor_id',
		'won_user',
		'part_id',
		'memo',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = ['part', 'vendor'];

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
		$val->add_field('item_count', 'Item Count', 'required|valid_string[numeric]|max_length[3]');
		$val->add_field('price', 'Price', 'required|valid_string[numeric]|max_length[5]');
		//$val->add_field('won_user', 'Won User', 'required|max_length[20]');
		$val->add_field('memo', 'Memo', 'max_length[60]');

		return $val;
	}

}
