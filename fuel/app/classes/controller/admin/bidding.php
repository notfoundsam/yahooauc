<?php

use Yahoo\Auction\Browser as Browser;
use Yahoo\Auction\Exceptions\BrowserLoginException as BrowserLoginException;
use Yahoo\Auction\Exceptions\BrowserException as BrowserException;
use Yahoo\Auction\Exceptions\ParserException as ParserException;

class Controller_Admin_Bidding extends Controller_Admin
{
	public function action_index($page = 1)
	{
		try
		{
			$browser = new Browser($this->USER_NAME, $this->USER_PASS, $this->APP_ID, $this->COOKIE_JAR);
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
		}
		catch (BrowserLoginException $e)
		{
			Session::set_flash('alert', [
				'status'  => 'danger',
				'message' => e("Login error: ".$e->getMessage())
			]);
		}
		catch (BrowserException $e)
		{
			Session::set_flash('alert', [
				'status'  => 'danger',
				'message' => e("Browser error: ".$e->getMessage())
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
		$this->template->content = View::forge('admin/bidding/index', ['result' => $result]);
	}
}
