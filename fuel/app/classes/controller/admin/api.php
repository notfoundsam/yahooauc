<?php

class Controller_Admin_Api extends Controller_Rest
{
	protected $rest_format = 'json';

	public function before()
	{
		parent::before();
		
		if (!\Auth::check() && !\Input::is_ajax()){
			throw new HttpNotFoundException;
		}

	}
	public function post_refresh()
	{
		$result = 0;
		$val_error = [];
		$auc_ids = [];
		$page = \Input::post('pages'); // Get from post request
		// Log::debug(\Input::post('pages'));
		$auc_ids = [];
		$select = \DB::select('auc_id')->from('auctions')->order_by('id','desc')->limit(50*$page)->execute()->as_array();
		$user_id = \DB::select('id')->from('users')->where('username', '=', Config::get('my.main_bidder'))->execute()->as_array();

		foreach ($select as $value) {
			$auc_ids[] = $value['auc_id'];
		}
		
		$val = Model_Auction::validate();
		
		foreach (Parser::getWon() as $auc_id) {
			
			if ( !in_array($auc_id, $auc_ids) ){

				$auc_xml = Browser::getXmlObject($auc_id);
				// check errors!!!!! lot not found and other
				$auc_values = [];
				$auc_values['item_count'] = 1;
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
		}

		$this->response(['result' => $result, 'error' => $val_error]);
	}
}
