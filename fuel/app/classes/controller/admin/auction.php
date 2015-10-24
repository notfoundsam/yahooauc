<?php
class Controller_Admin_Auction extends Controller_Admin
{
	public function action_edit($id = null, $one = null, $two = null)
	{
		$redirect = $two ? $one.'/'.$two : $one;
		$auction = Model_Auction::find($id);
		$val = Model_Auction::validate_edit();

		if ($val->run())
		{
			$auction->item_count = Input::post('item_count');
			$auction->price = Input::post('price');
			$auction->memo = Input::post('memo');

			if (\Security::check_token() && $auction->save())
			{
				Session::set_flash('success', e('Updated auction #' . $auction->auc_id));

				Response::redirect('admin/'.$redirect);
			}

			else
			{
				Session::set_flash('error', e('Could not update auction #' . $auction->auc_id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$auction->item_count = $val->validated('item_count');
				$auction->price = $val->validated('price');
				$auction->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('auction', $auction, false);
		}
		
		$this->template->set_global('redirect', $redirect, false);
		$this->template->title = $auction->title;
		$this->template->content = View::forge('admin/auction/edit');

	}

	public function action_delete($id = null, $one = null, $two = null)
	{
		$redirect = $two ? $one.'/'.$two : $one;

		if ($auction = \Model_Auction::find($id) and \Security::check_token())
		{

			$auction->part_id = null;
			$auction->save();

			Session::set_flash('success', e('Deleted auction #'.$auction->auc_id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete auction #'.$auction->auc_id));
		}

		Response::redirect('admin/'.$redirect);
	}
}
