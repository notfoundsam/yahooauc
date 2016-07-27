<?php
class Controller_Admin_Ship_Create extends Controller_Admin
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
		$data['ships'] = Model_Part::find('all');
		$this->template->title = "Create Ship";
		$this->template->content = View::forge('admin/ship/create/index', $data);

	}
	
}
