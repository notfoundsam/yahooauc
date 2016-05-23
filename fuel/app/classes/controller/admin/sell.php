<?php

class Controller_Admin_Sell extends Controller_Admin
{

	public function action_index()
	{
		$data['items'] = Model_Part::find('all',[
			'where' => [
				'status' => \Config::get('my.status.sell.id'),
			],
			'related' => [
				'auctions' => [
					'related' => [
						'vendor'
					],
				],
			],
		]);
		$this->template->title = "Pay";
		$this->template->content = View::forge('admin/list', $data);

	}

	public function action_edit($id = null)
	{
		$pay = Model_Part::find($id);
		$val = Model_Part::validate('edit');

		if ($val->run())
		{
			$pay->status = Input::post('status');
			$pay->price = Input::post('price');
			$pay->ship_number = Input::post('ship_number');
			$pay->box_number = Input::post('box_number');
			$pay->tracking = Input::post('tracking');
			$pay->memo = Input::post('memo');

			if ($pay->save())
			{
				Session::set_flash('success', e('Updated pay #' . $id));

				Response::redirect('admin/pay');
			}

			else
			{
				Session::set_flash('error', e('Could not update pay #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$pay->status = $val->validated('status');
				$pay->price = $val->validated('price');
				$pay->ship_number = $val->validated('ship_number');
				$pay->box_number = $val->validated('box_number');
				$pay->tracking = $val->validated('tracking');
				$pay->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('pay', $pay, false);
		}

		$this->template->title = "pays";
		$this->template->content = View::forge('admin/pay/edit');

	}

	public function action_delete($id = null)
	{
		if ($pay = Model_Part::find($id))
		{
			$pay->delete();

			Session::set_flash('success', e('Deleted pay #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete pay #'.$id));
		}

		Response::redirect('admin/pay');

	}

}
