<?php

class Controller_Admin_Finance extends Controller_Admin
{

	public function action_index()
	{
		$pagination = \Pagination::forge('default', [
			'name'        => 'bootstrap3',
			'total_items' => \Model_Finance::count(),
			'per_page'    => 50,
			'uri_segment' => 'p',
			'num_links'   => 20,
		]);
		$conditions = array(
			'rows_limit'  => $pagination->per_page,
			'rows_offset' => $pagination->offset,
		);
		
		$finances = Model_Finance::find('all', $conditions);

		$auctions_sum = \DB::select(\DB::expr('SUM(price) AS auctions_sum'))->from('auctions')->join('users','LEFT')->on('users.id', '=', 'auctions.user_id')->where('username', Config::get('my.main_bidder'))->execute();
		$items_count  = \DB::select(\DB::expr('SUM(item_count) AS items_count'))->from('auctions')->join('users','LEFT')->on('users.id', '=', 'auctions.user_id')->where('username', Config::get('my.main_bidder'))->execute();
		$parts_sum    = \DB::select(\DB::expr('SUM(price) AS parts_sum'))->from('parts')->execute();
		$usd          = \DB::select(\DB::expr('SUM(Case When usd < 0 then usd else 0 end) AS usd'))->from('balances')->execute();
		$usd_balance  = \DB::select(\DB::expr('SUM(usd) AS usd_balance'))->from('balances')->execute();
		$jpy          = \DB::select(\DB::expr('SUM(jpy) AS jpy'))->from('balances')->execute();

		$balance  = $jpy[0]['jpy'];
		$balance -= $auctions_sum[0]['auctions_sum'];
		$balance -= $parts_sum[0]['parts_sum'];
		$balance -= $items_count[0]['items_count'] * Config::get('my.commission');

		$this->template->title   = "Finances";
		$this->template->content = View::forge('admin/finance/index', [
			'finances'    => $finances,
			'usd'         => $usd[0]['usd'],
			'usd_balance' => $usd_balance[0]['usd_balance'],
			'jpy'         => $jpy[0]['jpy'],
			'balance'     => $balance,
		]);

	}

	public function action_edit($id = null)
	{
		$paid = Model_Part::find($id);
		$val = Model_Part::validate('edit');

		if ($val->run())
		{
			$paid->status = Input::post('status');
			$paid->price = Input::post('price');
			$paid->ship_number = Input::post('ship_number');
			$paid->box_number = Input::post('box_number');
			$paid->tracking = Input::post('tracking');
			$paid->memo = Input::post('memo');

			if ($paid->save())
			{
				Session::set_flash('success', e('Updated paid #' . $id));

				Response::redirect('admin/paid');
			}

			else
			{
				Session::set_flash('error', e('Could not update paid #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$paid->status = $val->validated('status');
				$paid->price = $val->validated('price');
				$paid->ship_number = $val->validated('ship_number');
				$paid->box_number = $val->validated('box_number');
				$paid->tracking = $val->validated('tracking');
				$paid->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('paid', $paid, false);
		}

		$this->template->title = "paids";
		$this->template->content = View::forge('admin/paid/edit');

	}

	public function action_delete($id = null)
	{
		if ($paid = Model_Part::find($id))
		{
			$paid->delete();

			Session::set_flash('success', e('Deleted paid #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete paid #'.$id));
		}

		Response::redirect('admin/paid');

	}

}
