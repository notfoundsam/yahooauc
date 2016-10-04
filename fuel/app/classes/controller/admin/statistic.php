<?php

class Controller_Admin_Statistic extends Controller_Admin
{
	public function action_index()
	{
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
		$s_date = $date;
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

		$s_date;
		$s_date->modify('-3 month');
		foreach ($years as $year)
		{
			$months = DB::select(DB::expr('DISTINCT MONTH(won_date) as month'))
			->from('auctions')
			->where(DB::expr('YEAR(won_date) = ' . $year['year']))
			->order_by('won_date', 'asc')
			->execute()->as_array();

			foreach ($months as $month)
			{
				$s_year = $year['year'];
				$s_month = $month['month'];

				try
				{
					$d_date = new DateTime("{$s_year}-{$s_month}-01");

					if ($d_date > $s_date)
					{
						throw new CacheNotFoundException("Ignore cache", 1);
					}

					$statistic[$year['year']][$month['month']] = \Cache::get("yahoo.statistic.{$s_year}.{$s_month}");

				} catch (\CacheNotFoundException $e) {

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

					$m_stat = [
						'count' => $items[0]['count'],
						'price' => $sum,
						'aprox' => number_format($sum / $items[0]['count'])
					];

					$statistic[$year['year']][$month['month']] = $m_stat;

					if ($d_date > $s_date)
						continue;
					
					\Cache::set("yahoo.statistic.{$s_year}.{$s_month}", $m_stat, \Config::get('my.statistic_cache'));
				}
			}
		}

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
