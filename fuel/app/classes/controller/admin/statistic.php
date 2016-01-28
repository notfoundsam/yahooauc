<?php
class Controller_Admin_Statistic extends Controller_Admin
{
// private final String YEAR_QUERY = "SELECT DISTINCT YEAR(wonDate) FROM auction ORDER BY wonDate DESC";
	public function action_index()
	{
		$result = DB::select(DB::expr('YEAR(won_date) as year'))->from('auctions')->distinct(true)->order_by('won_date','desc')->execute();
		Profiler::console($result);
		foreach ($result as $row) {
			// Profiler::console($row);
			Log::debug($row['year']);
			
		}

		// $condition = [
		// 	'related' => [
		// 		'user' => [
		// 			'where' => [
		// 				'username' => Config::get('my.main_bidder')
		// 			]
		// 		]	
		// 	]
		// ];

		// $auc_count = \Model_Auction::count($condition);
		$item_count = DB::select(DB::expr('SUM(item_count) as count'))
			->from('auctions')
			->join('users','LEFT')
			->on('users.id', '=', 'auctions.user_id')
			->where('username', Config::get('my.main_bidder'))
			->execute()->as_array();

		$commission = $item_count[0]['count'] * Config::get('my.commission');

		$auc_price = DB::select(DB::expr('SUM(price) as price'))
			->from('auctions')
			->join('users','LEFT')
			->on('users.id', '=', 'auctions.user_id')
			->where('username', Config::get('my.main_bidder'))
			->execute()->as_array();

		$part_price = DB::select(DB::expr('SUM(price) as price'))
			->from('parts')
			->execute()->as_array();

		$price = $auc_price[0]['price'] + $part_price[0]['price'] + $commission;

		$not_paid_auc = DB::select(DB::expr('SUM(auctions.price) as price'))
			->from('auctions')
			->join('parts','LEFT')
			->on('parts.id', '=', 'auctions.part_id')
			->where('status', Config::get('my.status.pay'))
			->execute()->as_array();

		$not_paid_part = DB::select(DB::expr('SUM(price) as price'))
			->from('parts')
			->where('status', Config::get('my.status.pay'))
			->execute()->as_array();

// NOT_PAUMENT_SUMM = "SELECT ((SELECT SUM(price) FROM auction WHERE groupID = ANY(SELECT groupID FROM part WHERE partStatus = 0)) + (SELECT SUM(partPrice) FROM part WHERE partStatus = 0))";

		// Debug::dump($item_count);
		// Profiler::console($item_count[0]['count']);
		// Debug::dump($alternative_result);

		$this->template->title   = "Statistic";
		$this->template->content = View::forge('admin/statistic/index', [
			'item_count' => $item_count[0]['count'],
			'commission' => $commission,
			'price' => $price,
			'approx_price' => $price / $item_count[0]['count'],
			'not_paid_sum' => $not_paid_auc[0]['price'] + $not_paid_part[0]['price']
		]);
	}
}
