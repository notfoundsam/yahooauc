<?php
class Controller_Admin_Sort extends Controller_Admin
{
	public function before()
	{
		parent::before();

		if ($this->current_user->group->id != 6)
		{
			\Response::redirect('admin');
		}
	}

	public function action_index()
	{
		$data['items'] = \Model_Auction::find('all',[
			'where' => [
				'part_id' => null,
			],
			'related' => [
				'vendor' => [
					'order_by' => [
						'name' => 'DESC'
					],
				],
				'user' => [
		            'where' => [
		            	'username' => Config::get('my.main_bidder'),
		            ],
				],
			],
		]);

		$this->template->title = 'Sorting list';
		$this->template->content = View::forge('admin/sort/index', $data);
	}

	public function action_my()
	{
		$data["subnav"] = array('index'=> 'active' );
		$data['items'] = \Model_Auction::find('all',[
			'where' => [
				'part_id' => null,
			],
			'related' => [
				'vendor',
				'user' => [
		            'where' => [
		            	'username' => Config::get('my.second_bidder'),
		            ],
				],
			],
		]);
		$this->template->title = 'Admin/sort &raquo; Index';
		$this->template->content = View::forge('admin/sort/index', $data);
	}

	public function action_edit($id = null, $one = null, $two = null)
	{
		$redirect = $two ? $one.'/'.$two : $one;
		$auction = Model_Auction::find($id, ['related' => ['vendor']]);
		$val = Model_Auction::validate_edit();
		$val->add_field('user_id', 'User Id', 'required|valid_string[numeric]');

		if ($val->run())
		{
			$auction->item_count = Input::post('item_count');
			$auction->price = Input::post('price');
			$auction->user_id = Input::post('user_id');
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
				$auction->vendor_id = $val->validated('vendor_id');
				$auction->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('auction', $auction, false);
		}
		
		$this->template->set_global('redirect', $redirect, false);
		$this->template->title = $auction->title;
		$this->template->content = View::forge('admin/sort/edit');

	}
}
