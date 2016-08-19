<?php
class Model_Ship extends \Orm\Model
{
	protected static $_table_name = 'ships';

	protected static $_primary_key = ['shipNumber'];

	protected static $_properties = array(
		'shipNumber',
		'shipAuctionID',
		'partStatus',
	);

	protected static $_conditions = [
		'order_by' => [
			'shipNumber' => 'DESC'
		],
	];

	protected static $_has_many = [
		'parts' => [
			'model_to' => 'Model_Part',
			'key_from' => 'shipNumber',
			'key_to' => 'ship_number',
		],
	];

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('sell_id', 'Sell Id', 'required|max_length[10]');

		return $val;
	}

}
