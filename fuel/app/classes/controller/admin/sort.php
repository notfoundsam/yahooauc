<?php
class Controller_Admin_Sort extends Controller_Admin
{

	public function action_index()
	{
		$data['table'] = Parser::getWon();

		$data["subnav"] = array('index'=> 'active' );
		$data['items'] = Model_Auction::find('all',[
			'where' => [
				'part_id' => null,
			],
			'related' => [
				'vendor',
				'user' => [
		            'where' => [
		            	'username' => Config::get('my.main_bidder'),
		            ],
				],
			],
		]);
		$this->template->title = 'Admin/sort &raquo; Index';
		$this->template->content = View::forge('admin/sort/index', $data);
	}

	public function action_my()
	{
		$data["subnav"] = array('index'=> 'active' );
		$data['items'] = Model_Auction::find('all',[
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
}
