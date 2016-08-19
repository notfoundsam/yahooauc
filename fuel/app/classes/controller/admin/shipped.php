<?php
class Controller_Admin_Shipped extends Controller_Admin
{
	
	public function action_index($id = null)
	{
		$items = null;
		$count = 0;

		$ship = \Model_Ship::find('first', [
			'related' => [
				'parts' => [
					'related' => [
						'auctions' => [
							'related' => [
								'vendor'
							]
						]
					]
				]
			],
			'where' => [
				'shipNumber' => $id
			]
		]);

		if ($ship)
		{
			$ship_count = DB::select(DB::expr('SUM(item_count) as count'))
				->from('auctions')
				->join('parts','LEFT')
				->on('parts.id', '=', 'auctions.part_id')
				->where('ship_number', $id)
				->execute()->as_array();

			$count = $ship_count[0]['count'];
			$items = $ship->parts;
		}

		$this->template->title = "Shipped";
		$this->template->content = View::forge('admin/list', [
			'ships' => \Model_Ship::find('all'),
			'items' => $items,
			'ship_id' => $id,
			'count_in_part' => $count,
		]);
	}
}
