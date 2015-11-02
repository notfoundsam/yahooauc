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

		$table = [];

		$html = new Simple_Html_Dom;
		// $html = str_get_html(Browser::getBody($r_biding_url));
		$html = str_get_html(Browser::getBodyBidding());
		$a_pages = [];

Log::debug($html);

		if ($p_t1 = $html->find('table', 3))
		{
			if ($p_t2 = $p_t1->find('table', 3))
			{
				if ($p_td = $p_t2->find('td', 0))
				{
					$pages = $p_td->find('a');
					foreach($pages as $page) {
			   			$a_pages[] = $page->innertext;
					}
				}
			}	
		}

		$table['pages'] = $a_pages;

		$a_auctions = [];

		if ($a_t1 = $html->find('table', 3))
		{
			Profiler::console($html->find('table', 3));
			if ($a_t2 = $a_t1->find('table', 4))
			{
				if ($auctions = $a_t2->find('tr'))
				{
					foreach($auctions as $key => $item) {
						$a_tr = [];
						if ($key == 0)
							continue;
						for ($i=0; $i < 6; $i++) { 
							$a_tr[] = strip_tags($item->find('td', $i)->innertext);
						}
						$a_auctions[] = $a_tr;
					}
				}
				else
				{
					
				}
			}	
		}

		
    	
		$this->template->title = 'Dashboard';
		$this->template->content = View::forge('admin/dashboard');
	}

}
