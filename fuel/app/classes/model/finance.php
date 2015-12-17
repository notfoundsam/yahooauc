<?php
class Model_Finance extends \Orm\Model
{
	protected static $_table_name = 'balances';

	protected static $_properties = array(
		'id',
		'operationData',
		'usd',
		'jpy',
		'memo',
	);

	protected static $_conditions = [
		'order_by' => [
			'id' => 'DESC'
		],
	];

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		// $val->add_field('name', 'Name', 'required|max_length[40]');
		// $val->add_field('by_now', 'By Now', 'required|valid_string[numeric]');
		// $val->add_field('post_index', 'Post Index', 'max_length[20]');
		// $val->add_field('address', 'Address', 'max_length[80]');
		// $val->add_field('color', 'Color', 'max_length[10]');
		// $val->add_field('memo', 'Memo', 'max_length[200]');

		return $val;
	}

}
