<?php

class Controller_Admin_Bidding extends Controller_Admin
{
	public function action_index($page = null)
	{
		$val_error = [];

		try
		{
			$browser = new Browser();
			$data['table'] =  $browser->bidding($page);
		}
		catch (BrowserLoginException $e)
		{
			Session::set_flash('error', e("Login error: ".$e->getMessage()));
		}
		catch (ParserException $e)
		{
			Session::set_flash('error', e("Parser error: ".$e->getMessage()));
		}
		
		$this->template->title = 'Bidding list';
		$this->template->content = View::forge('admin/bidding/index', $data);
	}
}
