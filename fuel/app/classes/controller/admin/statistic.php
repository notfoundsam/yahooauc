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
		
		$statistic = [];

		$years = DB::select(DB::expr('DISTINCT YEAR(won_date) as year'))
			->from('auctions')
			->order_by('won_date', 'desc')
			->execute()->as_array();

		foreach ($years as $year)
		{
			$months = DB::select(DB::expr('DISTINCT MONTH(won_date) as month'))
			->from('auctions')
			->where(DB::expr('YEAR(won_date) = ' . $year['year']))
			->order_by('won_date', 'asc')
			->execute()->as_array();

			foreach ($months as $month)
			{
				$items = DB::select(DB::expr('SUM(item_count) as count'))
					->from('auctions')
					->join('users','LEFT')
					->on('users.id', '=', 'auctions.user_id')
					->where_open()
					->where(DB::expr('YEAR(won_date) = ' . $year['year']))
					->and_where(DB::expr('MONTH(won_date) = ' . $month['month']))
					->and_where('username', Config::get('my.main_bidder'))
					->where_close()
					->execute()->as_array();

				$c = $items[0]['count'];

				$prices = DB::select(DB::expr('SUM(price) as price'))
					->from('auctions')
					->join('users','LEFT')
					->on('users.id', '=', 'auctions.user_id')
					->where_open()
					->where(DB::expr('YEAR(won_date) = ' . $year['year']))
					->and_where(DB::expr('MONTH(won_date) = ' . $month['month']))
					->and_where('username', Config::get('my.main_bidder'))
					->where_close()
					->execute()->as_array();

				$p = $prices[0]['price'];

				$parts = DB::select(DB::expr('SUM(price) as price'))
					->from('parts')
					->where(DB::expr('id = ANY (SELECT DISTINCT part_id FROM auctions WHERE YEAR(won_date) = ' . $year['year'] . ' AND MONTH(won_date) = ' . $month['month'] . ')'))
					->execute()->as_array();

				$pr = $parts[0]['price'];

				$sum = $p + $pr + $c * Config::get('my.commission');

				$statistic[$year['year']][$month['month']]['count'] = $items[0]['count'];
				$statistic[$year['year']][$month['month']]['price'] = $sum;
				$statistic[$year['year']][$month['month']]['aprox'] = number_format($sum / $items[0]['count']);
			}
		}

// SELECT DISTINCT YEAR(wonDate) FROM auction ORDER BY wonDate DESC
		// Debug::dump($today_won[0]['count']);
		// Debug::dump($yesterday_won);
		// Debug::dump(DB::last_query());
		// Debug::dump($next_date);
		// Debug::dump($years);
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
			'statistic' => $statistic,
		]);
	}
}
