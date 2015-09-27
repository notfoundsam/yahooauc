<?php
class Controller_Admin_Sort extends Controller_Admin
{

	public function action_index()
	{
		$data["subnav"] = array('index'=> 'active' );
		$data['items'] = Model_Auction::find('all',[
			'where' => [
				'part_id' => null,
			],
			'related' => [
				'vendor'
			],
		]);
		$this->template->title = 'Admin/sort &raquo; Index';
		$this->template->content = View::forge('admin/sort/index', $data);
	}
}
