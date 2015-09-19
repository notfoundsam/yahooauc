<?php
class Controller_Admin_Part extends Controller_Admin
{
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
}