<?php

use Yahooauc\Browser as Browser;
use Yahooauc\Exceptions\BrowserLoginException as BrowserLoginException;
use Yahooauc\Exceptions\BrowserException as BrowserException;
use Yahooauc\Exceptions\ParserException as ParserException;

class Controller_Admin_Bidding extends Controller_Admin
{
	public function action_index($page = 1)
	{
		$result = [];

		try
		{
			$browser = new Browser($this->USER_NAME, $this->USER_PASS, $this->APP_ID, $this->COOKIE_JAR, \Config::get('my.rmccue'));

			for ($page = 1; $page < 5; $page++)
			{ 
				$auctions = $browser->getBiddingLots($page);

				if (empty($auctions))
				{
					break;
				}
				else
				{
					$result = array_merge($result, $auctions);
				}
			}
			
			$cookieJar = $browser->getCookie();
			\Cache::set('yahoo.cookies', $cookieJar, \Config::get('my.yahoo.cookie_exp'));

			foreach ($result as $key => $value)
			{
				$auc_id = $result[$key]['id'];

				try
				{
					$result[$key]['images'] = \Cache::get('yahoo.images.' . $auc_id);
				}
				catch (\CacheNotFoundException $e)
				{
					$images = $browser->getAuctionImgsUrl($auc_id);

					$result[$key]['images'] = $images;

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
