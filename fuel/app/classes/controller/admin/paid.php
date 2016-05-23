<?php
class Controller_Admin_Paid extends Controller_Admin
{

	public function action_index()
	{
		$data['items'] = Model_Part::find('all',[
			'where' => [
				'status' => Config::get('my.status.paid.id'),
			],
			'related' => [
				'auctions' => [
					'related' => [
						'vendor'
					],
				],
			],
		]);

		$this->template->title = "Paid";
		$this->template->content = View::forge('admin/list', $data);
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
