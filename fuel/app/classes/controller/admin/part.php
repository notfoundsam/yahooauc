<?php
class Controller_Admin_Part extends Controller_Admin
{
	public function action_edit($id = null, $one = null, $two = null)
	{
		$redirect = $two ? $one.'/'.$two : $one;
		$part = Model_Part::find($id);
		$val = Model_Part::validate('edit');

		if ($val->run())
		{
			$part->status = Input::post('status');
			$part->price = Input::post('price');
			$part->box_number = Input::post('box_number');
			$part->tracking = Input::post('tracking');
			$part->memo = Input::post('memo');

			if ($part->save())
			{
				Session::set_flash('success', e('Updated part #' . $id));

				Response::redirect('admin/'.$redirect);
			}

			else
			{
				Session::set_flash('error', e('Could not update part #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$part->status = $val->validated('status');
				$part->price = $val->validated('price');
				$part->box_number = $val->validated('box_number');
				$part->tracking = $val->validated('tracking');
				$part->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('part', $part, false);
		}

		$this->template->set_global('redirect', $redirect, false);
		$this->template->title = "Part";
		$this->template->content = View::forge('admin/part/edit');

	}
}