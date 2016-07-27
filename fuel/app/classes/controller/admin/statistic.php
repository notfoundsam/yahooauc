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
			->where('status', Config::get('my.status.pay.id'))
			->execute()->as_array();

		$not_paid_part = DB::select(DB::expr('SUM(price) as price'))
			->from('parts')
			->where('status', Config::get('my.status.pay.id'))
			->execute()->as_array();

		$on_hand = DB::select(DB::expr('SUM(auctions.item_count) as item_count'))
			->from('auctions')
			->join('parts','LEFT')
			->on('parts.id', '=', 'auctions.part_id')
			->where('status', Config::get('my.status.received.id'))
			->execute()->as_array();

		$won = DB::select(DB::expr('SUM(auctions.item_count) as item_count'))
			->from('auctions')
			->join('parts','LEFT')
			->on('parts.id', '=', 'auctions.part_id')
			->where('status', 'in', [Config::get('my.status.pay.id'), Config::get('my.status.paid.id')])
			->execute()->as_array();

// NOT_PAUMENT_SUMM = "SELECT ((SELECT SUM(price) FROM auction WHERE groupID = ANY(SELECT groupID FROM part WHERE partStatus = 0)) + (SELECT SUM(partPrice) FROM part WHERE partStatus = 0))";

		// Debug::dump($item_count);
		// Profiler::console($item_count[0]['count']);
		// Debug::dump($alternative_result);
		// 
		$date = new DateTime();
		$date->setTime(0, 6, 0);
		$cur_date = $date->format('Y-m-d H:i:s');
		$date->modify('+1 day');
		$next_date = $date->format('Y-m-d H:i:s');
		$date->modify('-2 day');
		$prev_date = $date->format('Y-m-d H:i:s');

		$today_won = DB::select(DB::expr('SUM(item_count) as count'))
			->from('auctions')
			->join('users','LEFT')
			->on('users.id', '=', 'auctions.user_id')
			->where_open()
			->where('username', Config::get('my.main_bidder'))
			->and_where('won_date', 'between', [$cur_date, $next_date])
			->where_close()
			->execute()->as_array();

		$yesterday_won = DB::select(DB::expr('SUM(item_count) as count'))
			->from('auctions')
			->join('users','LEFT')
			->on('users.id', '=', 'auctions.user_id')
			->where_open()
			->where('username', Config::get('my.main_bidder'))
			->and_where('won_date', 'between', [$prev_date, $cur_date])
			->where_close()
			->execute()->as_array();


		// Debug::dump($today_won[0]['count']);
		// Debug::dump($yesterday_won);
		// Debug::dump(DB::last_query());
		// Debug::dump($next_date);
		// Debug::dump($prev_date);
		// $date = DateTime::createFromFormat('j-M-Y', '15-Feb-2009');
		// $cur_day = DateTime::createFromFormat('Y-m-d', $ymd_date);
		// Debug::dump($date);
		// echo date_format($cur_day, 'd-m-Y');
		// $createDate = new DateTime();

// $strip = $createDate->format('Y-m-d');

		$this->template->title   = "Statistic";
		$this->template->content = View::forge('admin/statistic/index', [
			'item_count' => $item_count[0]['count'],
			'commission' => $commission,
			'price' => $price,
			'approx_price' => $price / $item_count[0]['count'],
			'not_paid_sum' => $not_paid_auc[0]['price'] + $not_paid_part[0]['price'],
			'on_hand' => $on_hand[0]['item_count'],
			'on_hand_won' => $on_hand[0]['item_count'] + $won[0]['item_count'],
			'today_won' => $today_won[0]['count'] ? $today_won[0]['count'] : 0,
			'yesterday_won' => $yesterday_won[0]['count'] ? $yesterday_won[0]['count'] : 0,
		]);
	}
}
