<?php

class Controller_Base extends Controller_Template
{

	protected $current_user = null;

	public function before()
	{
		if (Agent::is_mobiledevice())
		{
			\Response::redirect('mobile/index.html');
		}

		parent::before();

		foreach (\Auth::verified() as $driver)
		{
			if (($id = $driver->get_user_id()) !== false)
			{
				$this->current_user = Model\Auth_User::find($id[1]);
			}
			break;
		}

		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
	}
}
