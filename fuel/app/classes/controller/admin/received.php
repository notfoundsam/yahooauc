<?php
class Controller_Admin_Received extends Controller_Admin
{

	public function action_index()
	{
		$data['items'] = Model_Part::find('all',[
			'where' => [
				'status' => \Config::get('my.status.received'),
			],
			'related' => 'auctions'
		]);
		$this->template->title = "Received";
		$this->template->content = View::forge('admin/universal', $data);

	}

	public function action_edit($id = null)
	{
		$received = Model_Part::find($id);
		$val = Model_Part::validate('edit');

		if ($val->run())
		{
			$received->status = Input::post('status');
			$received->price = Input::post('price');
			$received->ship_number = Input::post('ship_number');
			$received->box_number = Input::post('box_number');
			$received->tracking = Input::post('tracking');
			$received->memo = Input::post('memo');

			if ($received->save())
			{
				Session::set_flash('success', e('Updated received #' . $id));

				Response::redirect('admin/received');
			}

			else
			{
				Session::set_flash('error', e('Could not update received #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$received->status = $val->validated('status');
				$received->price = $val->validated('price');
				$received->ship_number = $val->validated('ship_number');
				$received->box_number = $val->validated('box_number');
				$received->tracking = $val->validated('tracking');
				$received->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('received', $received, false);
		}

		$this->template->title = "receiveds";
		$this->template->content = View::forge('admin/received/edit');

	}

	public function action_delete($id = null)
	{
		if ($received = Model_Part::find($id))
		{
			$received->delete();

			Session::set_flash('success', e('Deleted received #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete received #'.$id));
		}

		Response::redirect('admin/received');

	}

}
