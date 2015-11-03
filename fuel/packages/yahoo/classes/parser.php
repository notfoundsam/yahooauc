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
	private static $_won_url = 'http://closeduser.auctions.yahoo.co.jp/jp/show/mystatus?select=won';
	private static $_bidding_url = 'http://openuser.auctions.yahoo.co.jp/jp/show/mystatus?select=bidding';
	private static $_jp = array("円", "分", "時間", "日");
	private static $_en   = array("yen", "min", "hour", "day");

	public static function getBidding($page = null)
	{
		$r_biding_url = ($page) ? static::$_bidding_url.'picsnum=50&apg='.$page : static::$_bidding_url;

		$table = [];
		$paging = 4;
		$html = new Simple_Html_Dom;
		$html = str_get_html(Browser::getBody($r_biding_url));
		// $html = str_get_html(Browser::getBodyBidding());
		$a_pages = [];

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
								$a_tr[] = end((explode('/', $item->find('td', $i)->children(0)->href)));
							}
							if ($i == 1 || $i == 5)
							{
								$a_tr[] = str_replace(static::$_jp, static::$_en, strip_tags($item->find('td', $i)->innertext));
							}
							else
							{
								$a_tr[] = strip_tags($item->find('td', $i)->innertext);
							}
						}
						$a_auctions[] = $a_tr;
					}
				}
				else
				{
					
				}
			}	
		}

		$table['auctions'] = $a_auctions;
		
		return $table;
	}

	// return auc_id array
	public static function getWon($page = null)
	{
		$r_won_url = ($page) ? static::$_won_url.'picsnum=50&apg='.$page : static::$_won_url;

		$table = [];

		$html = new Simple_Html_Dom;
		$html = str_get_html(Browser::getBody($r_won_url));
		// $html = str_get_html(Browser::getBodyWon());
		
		$auc_id = [];

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

					$auc_id[] = strip_tags($a_td[1]->innertext);
				}
			}	
		}

		return $auc_id;
	}
}
