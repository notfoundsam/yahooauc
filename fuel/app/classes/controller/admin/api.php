<?php

class Controller_Admin_Api extends Controller_Rest
{
	// protected $rest_format = 'json';

	public function before()
	{
		parent::before();
		
		if (!\Auth::check() && !\Input::is_ajax()){
			throw new HttpNotFoundException;
		}

	}
	public function post_refresh()
	{
		Log::debug('REFRESH CONTROLLER');
		$page = 1; // Get from post request
		$auc_ids = \DB::select('auc_id')->from('auctions')->order_by('id','desc')->limit(50*$page)->execute()->as_array();

		foreach ($auc_ids as $auc_id) {
			Log::debug($auc_id['auc_id']);
		}
		// $data = Parser::getWon();

		// return $response;
		return 'ok';
	}
}
