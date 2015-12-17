<?php
class Controller_Admin_Finance extends Controller_Admin
{

	public function action_index()
	{
		$finance = Model_Finance::find('all');
		$usd = \DB::select(\DB::expr('SUM(Case When usd < 0 then usd else 0 end) AS usd'))->from('balances')->execute()->as_array();
		$usd_balance = \DB::select(\DB::expr('SUM(usd) AS usd_balance'))->from('balances')->execute()->as_array();
		$jpy = \DB::select(\DB::expr('SUM(jpy) AS jpy'))->from('balances')->execute()->as_array();
		Profiler::console($usd);
		$this->template->title = "Finances";
		$this->template->content = View::forge('admin/finance/index', [
			'finance' => $finance,
			'usd' => $usd[0]['usd'],
			'usd_balance' => $usd_balance[0]['usd_balance'],
			'jpy' => $jpy[0]['jpy']
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
