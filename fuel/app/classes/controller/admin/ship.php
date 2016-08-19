<?php
class Controller_Admin_Ship extends Controller_Admin
{

	public function action_index()
	{
		if (Input::method() == 'POST')
		{
			$val = \Model_Ship::validate('default');

			$values['sell_id'] = \Input::post('sell_id');

			if ($val->run($values))
			{
				$ship = \Model_Ship::forge();

				$parts = Model_Part::find('all',[
					'where' => [
						'status' => \Config::get('my.status.ship.id'),
					],
				]);

				try
				{
					\DB::start_transaction();

					$ship->shipAuctionID = $val->validated('sell_id');
					$ship->partStatus = 4;

					if ( !$ship->save() )
					{
						throw new Exception("Could not create ship", 1);
					}

					foreach ($parts as $p)
					{
						$p->status = \Config::get('my.status.shipped.id');
						$p->ship_number = $ship->shipNumber;

						if ( !$p->save() )
						{
							throw new Exception("Could not save part ID:".$p->id, 1);
						}
					}

					\DB::commit_transaction();

					Session::set_flash('alert', [
						'status'  => 'success',
						'message' =>'Ship was successfully created'
					]);

				} catch (\Exception $e) {

					DB::rollback_transaction();
					Session::set_flash('alert', [
						'status'  => 'danger',
						'message' => $e->getMessage(),
					]);
				}
			}
			else
			{
				Session::set_flash('alert', [
					'status'  => 'danger',
					'message' =>'Check sell ID'
				]);
			}
		}

		$data['items'] = Model_Part::find('all',[
			'where' => [
				'status' => \Config::get('my.status.ship.id'),
			],
			'related' => [
				'auctions' => [
					'related' => [
						'vendor'
					],
				],
			],
		]);

		$ship_count = DB::select(DB::expr('SUM(item_count) as count'))
			->from('auctions')
			->join('parts','LEFT')
			->on('parts.id', '=', 'auctions.part_id')
			->where('status', Config::get('my.status.ship.id'))
			->execute()->as_array();

		$data['ship_count'] = $ship_count[0]['count'];

		$this->template->title = "Ship";
		$this->template->content = View::forge('admin/list', $data);

	}

	public function action_edit($id = null)
	{
		$ship = Model_Part::find($id);
		$val = Model_Part::validate('edit');

		if ($val->run())
		{
			$ship->status = Input::post('status');
			$ship->price = Input::post('price');
			$ship->ship_number = Input::post('ship_number');
			$ship->box_number = Input::post('box_number');
			$ship->tracking = Input::post('tracking');
			$ship->memo = Input::post('memo');

			if ($ship->save())
			{
				Session::set_flash('success', e('Updated ship #' . $id));

				Response::redirect('admin/ship');
			}

			else
			{
				Session::set_flash('error', e('Could not update ship #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$ship->status = $val->validated('status');
				$ship->price = $val->validated('price');
				$ship->ship_number = $val->validated('ship_number');
				$ship->box_number = $val->validated('box_number');
				$ship->tracking = $val->validated('tracking');
				$ship->memo = $val->validated('memo');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('ship', $ship, false);
		}

		$this->template->title = "ships";
		$this->template->content = View::forge('admin/ship/edit');

	}

	public function action_delete($id = null)
	{
		if ($ship = Model_Part::find($id))
		{
			$ship->delete();

			Session::set_flash('success', e('Deleted ship #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete ship #'.$id));
		}

		Response::redirect('admin/ship');

	}

}
