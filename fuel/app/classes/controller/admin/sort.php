<?php
class Controller_Admin_Sort extends Controller_Admin
{

	public function action_index()
	{
		$data['sorts'] = Model_Auction::find('all',[
			'where' => [
				'status' => 4,
			]
		]);
		$this->template->title = "Sort";
		$this->template->content = View::forge('admin/sort/index', $data);

	}

	/*public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_sort::validate('create');

			if ($val->run())
			{
				$sort = Model_sort::forge(array(
					'item_count' => Input::post('item_count'),
					'auc_id' => Input::post('auc_id'),
					'description' => Input::post('description'),
					'price' => Input::post('price'),
					'won_date' => Input::post('won_date'),
					'vendor' => Input::post('vendor'),
					'won_user' => Input::post('won_user'),
					'part_id' => Input::post('part_id'),
					'memo' => Input::post('memo'),
				));

				if ($sort and $sort->save())
				{
					Session::set_flash('success', e('Added sort #'.$sort->id.'.'));

					Response::redirect('admin/sort');
				}

				else
				{
					Session::set_flash('error', e('Could not save sort.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "sorts";
		$this->template->content = View::forge('admin/sort/create');

	}*/

	public function action_edit($id = null)
	{
		$sort = Model_Auction::find($id);
		$val = Model_Auction::validate('edit');

		if ($val->run())
		{
			$sort->item_count = Input::post('item_count');
			$sort->auc_id = Input::post('auc_id');
			$sort->description = Input::post('description');
			$sort->price = Input::post('price');
			$sort->won_date = Input::post('won_date');
			$sort->vendor = Input::post('vendor');
			$sort->won_user = Input::post('won_user');
			$sort->part_id = Input::post('part_id');
			$sort->memo = Input::post('memo');

			if ($sort->save())
			{
				Session::set_flash('success', e('Updated sort #' . $id));

				Response::redirect('admin/sort');
			}

			else
			{
				Session::set_flash('error', e('Could not update sort #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$sort->item_count = $val->validated('item_count');
				$sort->auc_id = $val->validated('auc_id');
				$sort->description = $val->validated('description');
				$sort->price = $val->validated('price');
				$sort->won_date = $val->validated('won_date');
				$sort->vendor = $val->validated('vendor');
				$sort->won_user = $val->validated('won_user');
				$sort->part_id = $val->validated('part_id');
				$sort->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('sort', $sort, false);
		}

		$this->template->title = "sorts";
		$this->template->content = View::forge('admin/sort/edit');

	}

	/*public function action_delete($id = null)
	{
		if ($sort = Model_Auction::find($id))
		{
			$sort->delete();

			Session::set_flash('success', e('Deleted sort #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete sort #'.$id));
		}

		Response::redirect('admin/sort');

	}*/

}
