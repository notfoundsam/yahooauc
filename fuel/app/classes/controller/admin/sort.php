<?php
class Controller_Admin_Sort extends Controller_Admin
{

	public function action_index()
	{
		// $data['table'] = Parser::getWon();

		// $data["subnav"] = array('index'=> 'active' );
		

		if (Input::method() == "POST")
		{
			if ( $ids = \Input::post('ids') )
			{
				$part_id = null;

				if ($combine_id = (int) \Input::post('part_id'))
				{
					$part_id = $combine_id;
				}
				else
				{
					$part = new Model_part();

					if ($part->save())
					{
						$part_id = $part->id;
					}
				}

				foreach ($ids as $id)
				{
					$auction = \Model_Auction::find($id);
					$auction->part_id = $part_id;
					$auction->save();
				}
			}
		}

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

		$this->template->title = 'Admin/sort &raquo; Index';
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
}
