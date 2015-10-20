<?php

class Controller_Admin extends Controller_Base
{
	public $template = 'admin/template';

	public function before()
	{
		parent::before();

		if (Request::active()->controller !== 'Controller_Admin' or ! in_array(Request::active()->action, array('login', 'logout')))
		{
			if (Auth::check())
			{
				$admin_group_id = Config::get('auth.driver', 'Simpleauth') == 'Ormauth' ? 6 : 100;
				if ( ! Auth::member($admin_group_id))
				{
					Session::set_flash('error', e('You don\'t have access to the admin panel'));
					Response::redirect('/');
				}
			}
			else
			{
				Response::redirect('admin/login');
			}
		}
	}

	public function action_login()
	{
		// Already logged in
		Auth::check() and Response::redirect('admin');

		$val = Validation::forge();

		if (Input::method() == 'POST')
		{
			$val->add('email', 'Email or Username')
			    ->add_rule('required');
			$val->add('password', 'Password')
			    ->add_rule('required');

			if ($val->run())
			{
				if ( ! Auth::check())
				{
					if (Auth::login(Input::post('email'), Input::post('password')))
					{
						// assign the user id that lasted updated this record
						foreach (\Auth::verified() as $driver)
						{
							if (($id = $driver->get_user_id()) !== false)
							{
								// credentials ok, go right in
								$current_user = Model\Auth_User::find($id[1]);
								Session::set_flash('success', e('Welcome, '.$current_user->username));
								Response::redirect('admin');
							}
						}
					}
					else
					{
						$this->template->set_global('login_error', 'Login failed!');
					}
				}
				else
				{
					$this->template->set_global('login_error', 'Already logged in!');
				}
			}
		}

		$this->template->title = 'Login';
		$this->template->content = View::forge('admin/login', array('val' => $val), false);
	}

	/**
	 * The logout action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_logout()
	{
		Auth::logout();
		Response::redirect('admin');
	}

	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{



		// $user_id = \DB::select('id')->from('users')->where('username', '=', Config::get('my.main_bidder'))->execute()->as_array();
		// Profiler::console($user_id);

		// x421191361
		// try
		// {
		// 	$auc_xml = Browser::getXmlObject('xccc42119136');
		// }
		// catch (BrowserException $e)
		// {
		// 	Profiler::console($e->getMessage().' - '.$e->getCode());
		// }
		

		// Profiler::console($auc_xml);


		// $auc_test = array_reduce($auc_ids, 'array_merge', array());
		// Profiler::console($auc_test);
		// $auction = Browser::getXmlBody('x421191361');
		

		// Profiler::console($auction);


		// Profiler::console((string)$auction->Result->AuctionID);
		// Profiler::console((string)$auction->Result->AnsweredQAndANum);
		// Profiler::console((string)$auction->Result->Seller->Id);
    	
		$this->template->title = 'Dashboard';
		$this->template->content = View::forge('admin/dashboard');
	}

}
