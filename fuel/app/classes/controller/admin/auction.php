<?php
class Controller_Admin_Auction extends Controller_Admin
{
	public function action_edit($id = null, $one = null, $two = null)
	{
		$redirect = $two ? $one.'/'.$two : $one;
		$auction = Model_Auction::find($id);
		$val = Model_Auction::validate('edit');

		if ($val->run())
		{
			$auction->item_count = Input::post('item_count');
			$auction->price = Input::post('price');
			$auction->memo = Input::post('memo');

			if ($auction->save())
			{
				Session::set_flash('success', e('Updated auction #' . $id));

				Response::redirect('admin/'.$redirect);
			}

			else
			{
				Session::set_flash('error', e('Could not update auction #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$auction->item_count = $val->validated('status');
				$auction->price = $val->validated('price');
				$auction->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('auction', $auction, false);
		}

		$this->template->title = "auctions";
		$this->template->content = View::forge('admin/auction/edit');

	}
}