<?php

class Controller_Admin_Bidding extends Controller_Admin
{

	public function action_index($page = null)
	{
		$data['table'] = Parser::getBidding($page);
		$data["subnav"] = array('index'=> 'active' );
		$this->template->title = 'Admin/bidding &raquo; Index';
		$this->template->content = View::forge('admin/bidding/index', $data);
	}

}
