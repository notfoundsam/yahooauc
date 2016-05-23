<?php
class Controller_Admin_Ship_View extends Controller_Admin
{

	public function action_index()
	{
		$data['items'] = Model_Part::find('all',[
			'where' => [
				'status' => \Config::get('my.status.ship.id'),
			],
			'related' => [
				'auctions' => [
					'related' => [
						'vendor'
					],
				],
			],
		]);
		$this->template->title = "Ship";
		$this->template->content = View::forge('admin/list', $data);

	}

	public function action_edit($id = null)
	{
		$ship = Model_Part::find($id);
		$val = Model_Part::validate('edit');

		if ($val->run())
		{
			$ship->status = Input::post('status');
			$ship->price = Input::post('price');
			$ship->ship_number = Input::post('ship_number');
			$ship->box_number = Input::post('box_number');
			$ship->tracking = Input::post('tracking');
			$ship->memo = Input::post('memo');

			if ($ship->save())
			{
				Session::set_flash('success', e('Updated ship #' . $id));

				Response::redirect('admin/ship');
			}

			else
			{
				Session::set_flash('error', e('Could not update ship #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$ship->status = $val->validated('status');
				$ship->price = $val->validated('price');
				$ship->ship_number = $val->validated('ship_number');
				$ship->box_number = $val->validated('box_number');
				$ship->tracking = $val->validated('tracking');
				$ship->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('ship', $ship, false);
		}

		$this->template->title = "ships";
		$this->template->content = View::forge('admin/ship/edit');

	}

	public function action_delete($id = null)
	{
		if ($ship = Model_Part::find($id))
		{
			$ship->delete();

			Session::set_flash('success', e('Deleted ship #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete ship #'.$id));
		}

		Response::redirect('admin/ship');

	}

}
