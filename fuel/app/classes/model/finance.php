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
		$val->add_field('finance_usd', 'Income USD', 'valid_string[numeric]');
		$val->add_field('finance_jpy', 'Income JPY', 'valid_string[numeric]');
		$val->add_field('memo', 'comment', 'required|max_length[200]');

		return $val;
	}

}
