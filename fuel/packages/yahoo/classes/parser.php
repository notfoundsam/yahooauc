<?php
/**
 * Use Simple_Html_Dom for parse HTML
 *
 * PHP version 5
 *
 * Copyright (c) 2015, Zazimko Alexey <notfoundsam@gmail.com>
 * All rights reserved.
 *
 * @category Parse
 * @package  Yahoo
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/notfoundsam/yahooauc
 */

use Sunra\PhpSimple\HtmlDomParser;

class ParserException extends Exception {}

/**
 * Class get items in bidding
 *
 * @category Parse
 * @package  Yahoo
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/notfoundsam/yahooauc
 */
class Parser
{
	/*
     * Parser Class Object
     */
	// private static $_won_url = 'http://closeduser.auctions.yahoo.co.jp/jp/show/mystatus?select=won';
	// private static $_bidding_url = 'http://openuser.auctions.yahoo.co.jp/jp/show/mystatus?select=bidding';
	protected static $JP          = array("円", "分", "時間", "日");
	protected static $EN          = array("yen", "min", "hour", "day");
	protected static $BID_SUCCESS = '入札を受け付けました。あなたが現在の最高額入札者です。';
	protected static $PRICE_UP    = '申し訳ありません。開始価格よりも高い値段で入札してください。';
	protected static $AUCTION_WON = 'おめでとうございます!!　あなたが落札しました。';


	public static function checkLogin($body)
	{
		$html = new HtmlDomParser;
		$html = str_get_html($body);

		if ($p_result = $html->find('div[class=yjmthloginarea]', 0))
		{
			if (Config::get('my.yahoo_user') == trim($p_result->find('strong', 0)->innertext))
			{
				return true;
			}
		}
		return false;
	}

	public static function parseBiddingPage($body = null)
	{
		if (!$body)
		{
			throw new ParserException('Body of HTML Document is empty');
		}

		$table = [];
		$a_pages = [];
		$paging = 4;

		$html = new HtmlDomParser;
		$html = str_get_html($body);
		if ($p_t1 = $html->find('table', 3))
		{
			if ($p_t2 = $p_t1->find('table', 3))
			{
				if ($p_td = $p_t2->find('td', 0))
				{
					$pages = $p_td->find('a');
					foreach($pages as $page)
					{
						if ( !(int)$page->innertext ){
							$paging = 3;
							break;
						}
			   			$a_pages[] = $page->innertext;
					}
				}
			}	
		}

		$table['pages'] = $a_pages;

		$a_auctions = [];

		if ($a_t1 = $html->find('table', 3))
		{
			if ($a_t2 = $a_t1->find('table', $paging))
			{
				if ($auctions = $a_t2->find('tr'))
				{
					foreach($auctions as $key => $item)
					{
						$a_tr = [];
						if ($key == 0)
							continue;
						for ($i=0; $i < 6; $i++)
						{
							if ($i == 0)
							{
								$a_tr[] = end((explode('/', $item->find('td', $i)->find('a', 0)->href)));
							}
							if ($i == 1 || $i == 5)
							{
								$a_tr[] = str_replace(static::$JP, static::$EN, strip_tags($item->find('td', $i)->innertext));
							}
							else
							{
								$a_tr[] = strip_tags($item->find('td', $i)->innertext);
							}
						}
						$a_auctions[] = $a_tr;
					}
				}
			}	
		}

		$table['auctions'] = $a_auctions;
		
		return $table;
	}

	// return auc_id array
	public static function parseWonPage($body = null)
	{
		if (!$body)
		{
			throw new ParserException('Body of HTML Document is empty');
		}

		$ids = [];
		$html = new HtmlDomParser;
		$html = str_get_html($body);

		if ($a_t1 = $html->find('table', 3))
		{
			if ($auctions = $a_t1->find('table', 5)->children())
			{
				$first_tr = true;

				foreach($auctions as $key => $tr) {

					$a_tr = [];
					if (!$tr->children())
						continue;

					if ($first_tr){
						$first_tr = false;
						continue;
					}
					
					$a_td = $tr->children();

					$ids[] = strip_tags($a_td[1]->innertext);
				}
			}	
		}

		return $ids;
	}

	public static function getAuctionPageValues($body)
	{
		$page_values = [];
		$html = new HtmlDomParser;
		$html = str_get_html($body);

		if ($form = $html->find('form[method=post]', 0))
		{
			$inputs = $form->find('input[type=hidden]');

			foreach ($inputs as $input)
			{
				$page_values[] = ['name' => $input->name, 'value' => $input->value];
			}
		}
		else
		{
			throw new ParserException('Page POST form not found');
		}

		return $page_values;
	}

	public static function getResult($body)
	{
		$html = new HtmlDomParser;
		$html = str_get_html($body);

		if ($p_result = $html->find('div[id=modAlertBox]', 0))
		{
			if (static::$BID_SUCCESS == trim($p_result->find('strong', 0)->innertext))
			{
				return true;
			}
			else if (static::$AUCTION_WON == trim($p_result->find('strong', 0)->innertext))
			{
				return true;
			}
			else
			{
				throw new ParserException('Page says: '.$p_result->innertext);
			}
		}
		else if ($p_result = $html->find('div[id=modInfoBox]', 0))
		{
			if (static::$PRICE_UP == str_replace(' ', '', $p_result->find('strong', 0)->innertext))
			{

				throw new ParserException('Price goes up', 10);
			}
			else
			{
				throw new ParserException('Parser could not find result');
			}
		}
		return false;
	}
}
