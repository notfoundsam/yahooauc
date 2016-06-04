<?php

use Sunra\PhpSimple\HtmlDomParser;

class ParserException extends Exception {}

/**
 * Class parse Yahoo HTML pages
 * @category Parser
 * @package  Yahoo
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
 * @link     https://github.com/notfoundsam/yahooauc
 */
class Parser
{
	protected static $JP          = array(",", "円", "分", "時間", "日");
	protected static $EN          = array("", "", "min", "hour", "day");
	protected static $BID_SUCCESS = '入札を受け付けました。あなたが現在の最高額入札者です。';
	protected static $PRICE_UP    = '申し訳ありません。開始価格よりも高い値段で入札してください。';
	protected static $AUCTION_WON = 'おめでとうございます!!　あなたが落札しました。';

	/**
	 * Check Log In
	 * @param  string $body Body of HTML page
	 * @return bool         Return true if loggedin or false
	 */
	public static function checkLogin($body)
	{
		$html = new HtmlDomParser;
		$html = str_get_html($body);

		if ($p_result = $html->find('div[class=yjmthloginarea]', 0))
		{
			if (\Config::get('my.yahoo.user_name') == trim($p_result->find('strong', 0)->innertext))
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Take only Auction IDs from Auction won HTML page
	 * @param  string $body HTML page with won auctions
	 * @return array        Array of auction ids
	 * @throws ParserException Throw exception if HTML not given
	 */
	public static function parseWonPageNew($body = null)
	{
		if (!$body)
		{
			throw new ParserException('Body of HTML Document is empty');
		}

		$ids = [];
		$html = new HtmlDomParser;
		$html = str_get_html($body);
		$won_table = self::findTable ($html, Config::get('my.table.won'));

		if (!$won_table)
		{
			return null;
		}
		
		$first_tr = true;

		foreach ($won_table->children as $key => $tr)
		{
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

		return $ids;
	}

	/**
	 * Take all (ID, Title, Price etc.) from bidding HTML page
	 * @param  string $body HTML page with bidding auctions
	 * @return array        Array of bidding auctions
	 * @throws ParserException Throw exception if HTML not given
	 */
	public static function parseBiddingPageNew($body = null)
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
		$bidding_table = self::findTable ($html, Config::get('my.table.bidding'));

		if (!$bidding_table)
		{
			return null;
		}

		if ($p_t1 = $html->find('table', 3))
		{
			if ($p_t2 = $p_t1->find('table', 3))
			{
				if ($p_td = $p_t2->find('td', 0))
				{
					$pages = $p_td->find('a');
					foreach($pages as $page)
					{
						if ( !(int)$page->innertext ) {
							break;
						}
			   			$a_pages[] = $page->innertext;
					}
				}
			}	
		}

		$table['pages'] = $a_pages;

		$a_auctions = [];
		$first_tr = true;

		foreach ($bidding_table->children as $key => $tr)
		{
			$a_tr = [];
			if (!$tr->children())
				continue;

			if ($first_tr){
				$first_tr = false;
				continue;
			}

			foreach ($tr->children() as $i => $td)
			{
				if ($i > 5)
					break;
				if ($i == 0)
				{
					$a_tr[] = end((explode('/', $td->find('a', 0)->href)));
				}
				if ($i == 1 || $i == 5)
				{
					$a_tr[] = trim(str_replace(static::$JP, static::$EN, strip_tags($td->innertext)));
				}
				else
				{
					$a_tr[] = trim(strip_tags($td->innertext));
				}
			}
			
			$a_auctions[] = $a_tr;
		}

		$table['auctions'] = $a_auctions;
		
		return $table;
	}

	/**
	 * Parse HTML page for hidden fields in form
	 * @param  string $body Html with form
	 * @return array        Return array with pair name and value
	 * @throws ParserException Throw exception if POST form not found
	 */
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
		Log::debug('------ Parser start ------');
		Arrlog::arr_to_log($page_values);
		Log::debug('------- Parser end -------');

		return $page_values;
	}

	/**
	 * Check result of bid.
	 * @param  string $body    Html with auction result
	 * @return bool            Return true if bid was success
	 * @throws ParserException Throw exception if bid was not success
	 */
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

	/**
	 * Find table with auctions.
	 * @param  string $html Html with table of auctions
	 * @param  string $col  Find table with $col count
	 * @return object       Return HtmlDomParser element with table of auctions or null
	 */
	private static function findTable ($html = null, $col = null)
	{
		if ($tables = $html->find('table'))
		{
			foreach ($tables as $table)
			{
				if ( $t_children = $table->children() )
				{
					foreach ($t_children as $t_child)
					{
						if ( $t_child->tag = 'tbody')
						{
							if ( $t_body_children = $t_child->children() )
							{
								if ( count($t_body_children) == $col )
								{
									return $table;
								}
							}
						}
					}
				}
			}
		}
		return null;
	}
}
