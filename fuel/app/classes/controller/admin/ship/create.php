<?php
class Controller_Admin_Ship_Create extends Controller_Admin
{

	public function action_index()
	{
		$data['ships'] = Model_Part::find('all');
		$this->template->title = "Create Ship";
		$this->template->content = View::forge('admin/ship/create/index', $data);

	}
	
}
