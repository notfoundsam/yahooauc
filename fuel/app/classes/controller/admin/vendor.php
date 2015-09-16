<?php
class Controller_Admin_Vendor extends Controller_Admin
{

	public function action_index()
	{
		$data['vendors'] = Model_Vendor::find('all');
		$this->template->title = "Vendors";
		$this->template->content = View::forge('admin/vendor/index', $data);

	}

	public function action_view($id = null)
	{
		$data['vendor'] = Model_Vendor::find($id);

		$this->template->title = "Vendor";
		$this->template->content = View::forge('admin/vendor/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Vendor::validate('create');

			if ($val->run())
			{
				$vendor = Model_Vendor::forge(array(
					'name' => Input::post('name'),
					'by_now' => Input::post('by_now'),
					'post_index' => Input::post('post_index'),
					'address' => Input::post('address'),
					'color' => Input::post('color'),
					'memo' => Input::post('memo'),
				));

				if ($vendor and $vendor->save())
				{
					Session::set_flash('success', e('Added vendor #'.$vendor->id.'.'));

					Response::redirect('admin/vendor');
				}

				else
				{
					Session::set_flash('error', e('Could not save vendor.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Vendors";
		$this->template->content = View::forge('admin/vendor/create');

	}

	public function action_edit($id = null)
	{
		$vendor = Model_Vendor::find($id);
		$val = Model_Vendor::validate('edit');

		if ($val->run())
		{
			$vendor->name = Input::post('name');
			$vendor->by_now = Input::post('by_now');
			$vendor->post_index = Input::post('post_index');
			$vendor->address = Input::post('address');
			$vendor->color = Input::post('color');
			$vendor->memo = Input::post('memo');

			if ($vendor->save())
			{
				Session::set_flash('success', e('Updated vendor #' . $id));

				Response::redirect('admin/vendor');
			}

			else
			{
				Session::set_flash('error', e('Could not update vendor #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$vendor->name = $val->validated('name');
				$vendor->by_now = $val->validated('by_now');
				$vendor->post_index = $val->validated('post_index');
				$vendor->address = $val->validated('address');
				$vendor->color = $val->validated('color');
				$vendor->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('vendor', $vendor, false);
		}

		$this->template->title = "Vendors";
		$this->template->content = View::forge('admin/vendor/edit');

	}

	public function action_delete($id = null)
	{
		if ($vendor = Model_Vendor::find($id))
		{
			$vendor->delete();

			Session::set_flash('success', e('Deleted vendor #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete vendor #'.$id));
		}

		Response::redirect('admin/vendor');

	}

}
