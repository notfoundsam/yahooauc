<?php

use Yahoo\Auction\Browser as Browser;
use Yahoo\Auction\Exceptions\BrowserLoginException as BrowserLoginException;
use Yahoo\Auction\Exceptions\BrowserException as BrowserException;
use Yahoo\Auction\Exceptions\ParserException as ParserException;

class Controller_Admin_Api extends Controller_Rest
{
    protected $USER_NAME;
    protected $USER_PASS;
    protected $APP_ID;
    protected $COOKIE_JAR;

	protected $rest_format = 'json';
	protected $_status_code = [
		'login_success'  => 10,
		'alredy_logedin' => 20,
		'login_faild'    => 30,
		'logout'         => 40,
		'failed'         => 90,
		'success'        => 100,
	];

	public function before()
	{
		parent::before();

		// if (!\Input::is_ajax())
		// {
		// 	throw new HttpNotFoundException;
		// }

        $this->USER_NAME = \Config::get('my.yahoo.user_name');
        $this->USER_PASS = \Config::get('my.yahoo.user_pass');
        $this->APP_ID    = \Config::get('my.yahoo.user_appid');

        try
        {
            $this->COOKIE_JAR = \Cache::get('yahoo.cookies');
        }
        catch (CacheNotFoundException $e)
        {
            $this->COOKIE_JAR = null;
        }
	}

	public function auth()
	{
		if (Auth::check() || in_array(Request::active()->action, ['login', 'logout']))
		{
			return true;
		}

		return false;
	}

	public function post_check_login()
	{
		$result = [];

		foreach (\Auth::verified() as $driver)
		{
			if (($id = $driver->get_user_id()) !== false)
			{
				$result['current_user'] = Model\Auth_User::find($id[1])->username;
			}
			break;
		}
		$result['current_bidder'] = \Config::get('my.yahoo.user_name');

		$this->response([
			'status_code' => $this->_status_code['success'],
			'result' => $result
		]);
	}

	public function post_login()
	{
		$val = Validation::forge();

		$val->add('email', 'Email or Username')->add_rule('required');
		$val->add('password', 'Password')->add_rule('required');

		if ($val->run())
		{
			if (!Auth::check())
			{
				if (Auth::login(Input::post('email'), Input::post('password')))
				{
					// assign the user id that lasted updated this record
					foreach (\Auth::verified() as $driver)
					{
						if (($id = $driver->get_user_id()) !== false)
						{
							$result = [];
							$result['current_user'] = Model\Auth_User::find($id[1])->username;
							$result['current_bidder'] = \Config::get('my.yahoo.user_name');

							$this->response([
								'status_code' => $this->_status_code['login_success'],
								'result' => $result
							]);
						}
					}
				}
				else
				{
					$this->response(['status_code' => $this->_status_code['login_faild']]);
				}
			}
			else
			{
				$this->response(['status_code' => $this->_status_code['alredy_logedin']]);
			}
		}
		else
		{
			$this->response(['status_code' => $this->_status_code['login_faild']]);
		}
	}

	public function post_logout()
	{
		\Auth::logout();
		$this->response(['status_code' => $this->_status_code['logout']]);
	}

	public function post_bid()
	{
		$result = '';
		$val_error = [];
		$status_code = null;

		$val = Validation::forge();
		$val->add_field('auc_id', '[Lot ID]', 'required|trim|max_length[15]');
		$val->add_field('price', '[Price]', 'required|trim|valid_string[numeric]|max_length[6]');

		$bid_values['auc_id'] = \Input::post('auc_id');
		$bid_values['price'] = \Input::post('price');

		if ( $val->run($bid_values) )
		{
			try
			{
				$browser = new Browser($this->USER_NAME, $this->USER_PASS, $this->APP_ID, $this->COOKIE_JAR, \Config::get('my.rmccue'));
                $browser->bid($val->validated('auc_id'), $val->validated('price'));
                $result = 'Bid on '. $val->validated('auc_id'). ' successful';

                try
                {
                    \Cache::get('yahoo.images.' . $val->validated('auc_id'));
                }
                catch (\CacheNotFoundException $e)
                {
                    $images = $browser->getAuctionImgsUrl();
                    \Cache::set('yahoo.images.' . $val->validated('auc_id'), $images, 3600 * 24 * 10);
                }

                $cookieJar = $browser->getCookie();
                \Cache::set('yahoo.cookies', $cookieJar, \Config::get('my.yahoo.cookie_exp'));

			}
            catch (BrowserLoginException $e)
            {
                $val_error[] = e("Login error: ".$e->getMessage());
            }
			catch (BrowserException $e)
			{
				$val_error[] = e("Browser error: ".$e->getMessage());
			}
			catch (ParserException $e)
			{
				$val_error[] = e("Parser error: ".$e->getMessage());
			}
		}
		else
		{
			foreach ($val->error() as $error)
			{
				$val_error[] = $error->get_message();
			}
		}
		$this->response([
			'status_code' => $status_code,
			'result' => $result,
			'error' => implode('<br>', (array) $val_error)
		]);
	}

    public function get_bidding()
    {
        $page = \Input::get('page');

        try
        {
            $browser = new Browser($this->USER_NAME, $this->USER_PASS, $this->APP_ID, $this->COOKIE_JAR, \Config::get('my.rmccue'));
            
            $result = $browser->getBiddingLots($page);
            $cookieJar = $browser->getCookie();

            \Cache::set('yahoo.cookies', $cookieJar, \Config::get('my.yahoo.cookie_exp'));

            foreach ($result['lots'] as $key => $value)
            {
                $auc_id = $result['lots'][$key]['id'];

                try
                {
                    $result['lots'][$key]['images'] = \Cache::get('yahoo.images.' . $auc_id);
                }
                catch (\CacheNotFoundException $e)
                {
                    $images = $browser->getAuctionImgsUrl($auc_id);

                    $result['lots'][$key]['images'] = $images;

                    \Cache::set('yahoo.images.' . $auc_id, $images, 3600 * 24 * 10);
                }
            }

            $this->response([
                'status_code' => $this->_status_code['success'],
                'result' => $result
            ]);
        }
        catch (BrowserLoginException $e)
        {
            $this->response([
                'status_code' => $this->_status_code['failed'],
                'message' => e("Login error: ".$e->getMessage())
            ]);
        }
        catch (BrowserException $e)
        {
            $this->response([
                'status_code' => $this->_status_code['failed'],
                'message' => e("Browser error: ".$e->getMessage())
            ]);
        }
        catch (ParserException $e)
        {
            $this->response([
                'status_code' => $this->_status_code['failed'],
                'message' => e("Parser error: ".$e->getMessage())
            ]);
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
            $browser = new Browser($this->USER_NAME, $this->USER_PASS, $this->APP_ID, $this->COOKIE_JAR, \Config::get('my.rmccue'));

            foreach ($browser->getWonIds($page) as $auc_id) {
                
                if ( !in_array($auc_id, $auc_ids) )
                {
                    try
                    {
                        $auc_xml = $browser->getAuctionInfoAsXml($auc_id);

                        $auc_values = [
                            'auc_id'   => (string) $auc_xml->Result->AuctionID,
                            'title'    => (string) $auc_xml->Result->Title,
                            'price'    => isset($auc_xml->Result->TaxinPrice) ? (int) $auc_xml->Result->TaxinPrice : (int) $auc_xml->Result->Price,
                            'won_date' => Date::create_from_string( (string) $auc_xml->Result->EndTime, 'yahoo_date')->format('mysql'),
                            'user_id'  => $user_id[0]['id']
                        ];

                        $vendor_name = (string) $auc_xml->Result->Seller->Id;
                        $vendor_id = \DB::select('id')->from('vendors')->where('name', '=', $vendor_name)->execute()->as_array();
                        
                        if ( !empty($vendor_id) )
                        {
                            $auc_values['vendor_id'] = $vendor_id[0]['id'];
                        }
                        else
                        {
                            $v = Model_Vendor::forge()->set(['name' => $vendor_name, 'by_now' => 0]);

                            if ($v->save())
                            {
                                $auc_values['vendor_id'] = $v->id;
                            }
                        }
                        
                        if ( $val->run($auc_values) )
                        {
                            Model_Auction::forge()->set($auc_values)->save();
                            $result++;
                        }
                        else
                        {
                            foreach ($val->error() as $value)
                            {
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

	public function post_addvendor()
	{
		$result    = '';
		$val_error = [];

		$val = Model_Vendor::validate('create');

		$id                   = \Input::post('vendor_id');
		$values['name']       = \Input::post('vendor_name');
		$values['by_now']     = \Input::post('by_now');
		$values['post_index'] = \Input::post('post_index');
		$values['address']    = \Input::post('address');
		$values['color']      = \Input::post('color');
		$values['memo']       = \Input::post('comment');

		if ( $val->run($values) )
		{
			$vendor = ( $id && \Model_Vendor::find($id) ) ? \Model_Vendor::find($id) : \Model_Vendor::forge($values);

			$vendor->set($values);

			if ($vendor->save())
			{
				$result = $id ? 'Vendor was successfully updated' : 'New vendor was successfully created';
			}
			else
			{
				$val_error[] = 'Could not create new vendor';
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

	public function post_addfinance()
	{
		$result    = '';
		$val_error = [];

		$val = Model_Finance::validate('create');

		$values['usd']           = \Input::post('finance_usd') ? \Input::post('finance_usd') : 0;
		$values['jpy']           = \Input::post('finance_jpy') ? \Input::post('finance_jpy') : 0;
		$values['operationData'] = \Date::forge()->format('mysql');
		$values['memo']          = \Input::post('comment');

		if ( $val->run($values) )
		{
			$finance = \Model_Finance::forge($values);

			$finance->set($values);

			if ($finance->save())
			{
				$result = 'Record successfully created';
			}
			else
			{
				$val_error[] = 'Could not create new record';
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
}
