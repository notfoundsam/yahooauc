<?php

class Controller_Admin_Bidding extends Controller_Admin
{
	public function action_index($page = null)
	{
		$data['table'] = null;

		try
		{
			$browser = new Browser();
			$data['table'] =  $browser->bidding($page);
		}
		catch (BrowserLoginException $e)
		{
			Session::set_flash('alert', [
				'status'  => 'danger',
				'message' => e("Login error: ".$e->getMessage())
			]);
		}
		catch (ParserException $e)
		{
			Session::set_flash('alert', [
				'status'  => 'danger',
				'message' => e("Parser error: ".$e->getMessage())
			]);
		}
		
		$this->template->title = 'Bidding list';
		$this->template->content = View::forge('admin/bidding/index', $data);
	}
}
