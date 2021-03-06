<?php

class Controller_Admin_Won extends Controller_Admin
{

	public function action_index()
	{
		$data["subnav"] = array('index'=> 'active' );
		$data['items'] = Model_Auction::find('all', [
			'related' => [
				'user'
			],
			'where' => [
				'part_id' => null,
				'username' => 'vyacheslav',
			],
		]);
		$this->template->title = 'Admin/won &raquo; Index';
		$this->template->content = View::forge('admin/won/index', $data);
	}

}
