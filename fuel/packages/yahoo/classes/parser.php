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
    private static $_bidding_url = 'http://openuser.auctions.yahoo.co.jp/jp/show/mystatus?select=bidding';
    private static $_won_url = 'http://closeduser.auctions.yahoo.co.jp/jp/show/mystatus?select=won';

	public static function getBidding()
	{
		$table = [];

		$html = new Simple_Html_Dom;
		$html = str_get_html(Browser::getBodyBidding());

		$a_pages = [];
		$pages = $html->find('table', 3)->find('table', 3)->find('td', 0)->find('a');
		foreach($pages as $page) {
   			$a_pages[] = $page->innertext;
		}

		$table['pages'] = $a_pages;

		$auctions = $html->find('table', 3)->find('table', 4)->find('tr');

		$a_auctions = [];
		foreach($auctions as $key => $item) {
			$a_tr = [];
			if ($key == 0)
				continue;
			for ($i=0; $i < 6; $i++) { 
				$a_tr[] = strip_tags($item->find('td', $i)->innertext);
			}
			$a_auctions[] = $a_tr;
   			
		}

		$table['auctions'] = $a_auctions;
		
		return $table;
	}

	public static function getWon()
	{
		return Browser::getBodyWon();
	}
}