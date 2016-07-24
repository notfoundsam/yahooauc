<?php

class Controller_Admin_Api extends Controller_Rest
{
	protected $rest_format = 'json';

	public function before()
	{
		parent::before();
		
		if (!\Auth::check() && !\Input::is_ajax())
		{
			throw new HttpNotFoundException;
		}
	}

	public function post_refresh()
	{
		$result = 0;
		$val_error = [];
		$auc_ids = [];
		$page = (int)\Input::post('pages');
		$select = \DB::select('auc_id')->from('auctions')->order_by('id','desc')->limit(Config::get('my.task.last_won_limit'))->execute()->as_array();
		$user_id = \DB::select('id')->from('users')->where('username', Config::get('my.main_bidder'))->execute()->as_array();

		foreach ($select as $value) {
			$auc_ids[] = $value['auc_id'];
		}
		
		$val = Model_Auction::validate();
		
		try
		{
			$browser = new Browser();

			foreach ($browser->won($page) as $auc_id) {
				
				if ( !in_array($auc_id, $auc_ids) ){

					try
					{
						$auc_xml = $browser->getXmlObject($auc_id);

						$auc_values = [];
						$auc_values['auc_id'] = (string) $auc_xml->Result->AuctionID;
						$auc_values['title'] = (string) $auc_xml->Result->Title;
						$auc_values['price'] = (int) $auc_xml->Result->Price;
						$auc_values['won_date'] = Date::create_from_string( (string) $auc_xml->Result->EndTime , 'yahoo_date')->format('mysql');
						$auc_values['user_id'] = $user_id[0]['id'];

						$vendor_name = (string) $auc_xml->Result->Seller->Id;
						$vendor_id = \DB::select('id')->from('vendors')->where('name', '=', $vendor_name)->execute()->as_array();
						
						if ( !empty($vendor_id) )
						{
							$auc_values['vendor_id'] = $vendor_id[0]['id'];
						}
						else
						{
							if ( Model_Vendor::forge()->set(['name' => $vendor_name, 'by_now' => 0])->save() )
							{
								$vendor_id = \DB::select('id')->from('vendors')->where('name', '=', $vendor_name)->execute()->as_array();
								$auc_values['vendor_id'] = $vendor_id[0]['id'];
							}
						}
						
						if ( $val->run($auc_values) )
						{
							Model_Auction::forge()->set($auc_values)->save();
							$result++;
						}
						else
						{
							foreach ($val->error() as $value) {
								Log::error('Validation error in controller/admin/api.php: '.$value);
							}
							$val_error[] = "Could not save auction ".$auc_values['auc_id'];
						}
					}
					catch (BrowserException $e)
					{
						$val_error[] = "ID: ".$auc_id." Error: ".$e->getMessage();
					}
				}
			}
		}
		catch (BrowserLoginException $e)
		{
			$val_error[] = "Login error: ".$e->getMessage();
		}
		catch (ParserException $e)
		{
			$val_error[] = "Parser error: ".$e->getMessage();
		}

		$this->response(['result' => $result, 'error' => implode('<br>', (array) $val_error)]);
	}

	public function post_bid()
	{
		$result = '';
		$val_error = [];

		$val = Validation::forge();
		$val->add_field('auc_id', '[Lot ID]', 'required|max_length[10]');
		$val->add_field('price', '[Price]', 'required|valid_string[numeric]|max_length[5]');

		$bid_values['auc_id'] = \Input::post('auc_id');
		$bid_values['price'] = \Input::post('price');

		if ( $val->run($bid_values) )
		{
			try
			{
				$browser = new Browser();
				
				$auc_xml = $browser->getXmlObject($val->validated('auc_id'));

				if ((string) $auc_xml->Result->Status == 'open')
				{
					$price = $val->validated('price');
					$auc_url = (string) $auc_xml->Result->AuctionItemUrl;

					if ($browser->bid($price, $auc_url))
					{
						$result = 'Bid on '. $val->validated('auc_id'). ' successful';
					}
					else
					{
						$val_error[] = 'Needs to clean won pages or unknown result';
					}
				}
				else
				{
					$val_error[] = 'Auction '. $val->validated('auc_id'). ' have ended';
				}
			}
			catch (BrowserException $e)
			{
				$val_error[] = "ID: ".$val->validated('auc_id')." Error: ".$e->getMessage();
			}
			catch (ParserException $e)
			{
				if ($e->getCode() == 10)
				{
					$val_error[] = "ID: ".$val->validated('auc_id')." Error: ".$e->getMessage();
				}
				else
				{
					$val_error[] = $e->getMessage();
				}
			}
		}
		else
		{
			foreach ($val->error() as $error)
			{
				$val_error[] = $error->get_message();
			}
		}
		$this->response(['result' => $result, 'error' => implode('<br>', (array) $val_error)]);
	}

	public function post_updateauc()
	{
		$result    = '';
		$val_error = [];

		$val = Validation::forge();
		$val->add_field('id', '[ID]', 'required|valid_string[numeric]');
		$val->add_field('count', '[Count]', 'required|valid_string[numeric]|max_length[5]');
		$val->add_field('price', '[Price]', 'required|valid_string[numeric]|max_length[5]');
		$val->add('comment', '[Comment]');

		$values['id']      = \Input::post('id');
		$values['count']   = \Input::post('count');
		$values['price']   = \Input::post('price');
		$values['comment'] = \Input::post('comment');

		if ( $val->run($values) )
		{
			$auction = \Model_Auction::find($val->validated('id'));
			$auction->item_count = $val->validated('count');
			$auction->price      = $val->validated('price');
			$auction->memo       = $val->validated('comment');
			if ($auction->save())
			{
				$result = 'Auction ID: '.$auction->auc_id.' was successfully updated';
			}
			else
			{
				$val_error[] = 'Could not update auction ID: '.$auction->aucid;
			}
		}
		else
		{
			foreach ($val->error() as $error)
			{
				$val_error[] = $error->get_message();
			}
		}
		$this->response(['result' => $result, 'error' => implode('<br>', (array) $val_error)]);
	}

	public function post_deleteauc()
	{
		$result    = '';
		$val_error = [];

		$val = Validation::forge();
		$val->add_field('id', '[ID]', 'required|valid_string[numeric]');

		$values['id'] = \Input::post('id');

		if ( $val->run($values) )
		{
			$auction = \Model_Auction::find($val->validated('id'));
			$auction->part_id = null;
			if ($auction->save())
			{
				$result = 'Auction ID: '.$auction->auc_id.' was successfully deleted';
			}
			else
			{
				$val_error[] = 'Could not delete auction ID: '.$auction->auc_id;
			}
		}
		else
		{
			foreach ($val->error() as $error)
			{
				$val_error[] = $error->get_message();
			}
		}
		$this->response(['result' => $result, 'error' => implode('<br>', (array) $val_error)]);
	}

	public function post_updatepart()
	{
		$result    = '';
		$val_error = [];

		$val = Validation::forge();
		$val->add_field('id', '[ID]', 'required|valid_string[numeric]');
		$val->add_field('status', '[Status]', 'required|valid_string[numeric]');
		$val->add_field('price', '[Ship]', 'required|valid_string[numeric]|max_length[5]');
		$val->add_field('box', '[Box]', 'valid_string[numeric]|max_length[3]');
		$val->add('tracking', '[Tracking]');
		$val->add('comment', '[Comment]');

		$values['id']        = \Input::post('id');
		$values['status']    = \Input::post('status');
		$values['price']     = \Input::post('price');
		$values['box']       = \Input::post('box');
		$values['tracking']  = \Input::post('tracking');
		$values['comment']   = \Input::post('comment');

		if ( $val->run($values) )
		{
			$part = \Model_Part::find($val->validated('id'));
			$part->status     = $val->validated('status');
			$part->price      = $val->validated('price');
			$part->box_number = $val->validated('box');
			$part->tracking   = $val->validated('tracking');
			$part->memo       = $val->validated('comment');
			if ($part->save())
			{
				$result = 'Part ID: '.$part->id.' was successfully updated';
			}
			else
			{
				$val_error[] = 'Could not update part ID: '.$part->id;
			}
		}
		else
		{
			foreach ($val->error() as $error)
			{
				$val_error[] = $error->get_message();
			}
		}
		$this->response(['result' => $result, 'error' => implode('<br>', (array) $val_error)]);
	}


	public function post_deletepart()
	{
		$result    = '';
		$val_error = [];

		$val = Validation::forge();
		$val->add_field('id', '[ID]', 'required|valid_string[numeric]');

		$values['id'] = \Input::post('id');

		if ( $val->run($values) )
		{
			$part = \Model_Part::find($val->validated('id'));

			foreach ($part->auctions as $auction)
			{
				$auction->part_id = null;
				$auction->save();
			}
			$part_id = $part->id;

			if ($part->delete())
			{
				$result = 'Part ID: '.$part_id.' was successfully deleted';
			}
			else
			{
				$val_error[] = 'Could not delete part ID: '.$part_id;
			}
		}
		else
		{
			foreach ($val->error() as $error)
			{
				$val_error[] = $error->get_message();
			}
		}
		$this->response(['result' => $result, 'error' => implode('<br>', (array) $val_error)]);
	}

	public function post_createpart()
	{
		$result    = '';
		$val_error = [];
		$combine_id = (int) \Input::post('combine_id');
		$ids = \Input::post('ids');

		if ($ids)
		{
			$part_id = null;

			if ($combine_id && \Model_part::find($combine_id))
			{
				$part_id = $combine_id;
				$result = 'Part ID: '.$part_id.' was successfully updated';
			}
			else
			{
				$part = new \Model_part();

				if ($part->save())
				{
					$part_id = $part->id;
				}
				$result = 'Part ID: '.$part_id.' was successfully created';
			}

			foreach ($ids as $id)
			{
				$auction = \Model_Auction::find($id);
				$auction->part_id = $part_id;
				$auction->save();
			}
		}
		else
		{
			$val_error[] = 'Could not create new part';
		}

		$this->response(['result' => $result, 'error' => implode('<br>', (array) $val_error)]);
	}
}
