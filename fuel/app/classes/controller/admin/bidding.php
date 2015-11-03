<?php

class Controller_Admin_Bidding extends Controller_Admin
{

	public function action_index($page = null)
	{
		$data['table'] = Parser::getBidding($page);
		$this->template->title = 'Bidding list';
		$this->template->content = View::forge('admin/bidding/index', $data);
	}

}
