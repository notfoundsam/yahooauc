<?php
/**
 * Class login and grub HTML your yahoo.co.jp pages
 *
 * PHP version 5
 *
 * Copyright (c) 2015, Zazimko Alexey <notfoundsam@gmail.com>
 * All rights reserved.
 *
 * @category Grab
 * @package  Yahoo
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/notfoundsam/yahooauc
 */

/*
 * Class representing a HTTP request message
 * PEAR package should be installed
 */
require 'HTTP/Request2.php';
require 'HTTP/Request2/CookieJar.php';

class BrowserException extends Exception {}

/**
 * Class login and scraping your yahoo.co.jp pages
 *
 * @category Grab
 * @package  Yahoo
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/notfoundsam/yahooauc
 */
class Browser
{
    /*
     * HTTP_Request Class Object
     */
    private static $login = false;
    private static $select;
    protected static $rq;
    protected static $body;

    /**
     * init function
     *
     * @return void
     */
    public static function _init()
    {
    	static::$rq = new HTTP_Request2();

    	static::$select = DB::select()->from('yahoo')->where('userid', Config::get('my.yahoo_user'))->execute()->as_array();

    	if (empty(static::$select))
    	{
    		throw new BrowserException('user in config/my.yahoo_user not found in DB');
    	}
    	if (static::$select[0]['cookies'] && (static::$select[0]['updated_at'] > strtotime('-1 months')))
    	{
    		$jar = new HTTP_Request2_CookieJar();
        	$jar->unserialize(static::$select[0]['cookies']);
        	static::$rq->setCookieJar($jar);
    	}
    	else
    	{
    		static::$rq->setCookieJar(true);
	        static::$rq->setHeader(
	            'User-Agent',
	            'Mozilla/6.0 (Windows; U; Windows NT 6.0; ja; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1 (.NET CLR 3.5.30729)'
	        );
	        static::$rq->setHeader('Keep-Alive', 115);
	        static::$rq->setHeader('Connection', 'keep-alive');
	        static::login();
    	}
    }

    /**
    * login to yahoo 
    *
    */
    public static function login()
    {
    	static::$login = true;

    	// $select = DB::select()->from('yahoo')->where('userid', Config::get('my.yahoo_user'))->execute();

        $login_url = 'https://login.yahoo.co.jp/config/login?';
        $login_params = '.lg=jp&.intl=jp&.src=auc&.done=http://auctions.yahoo.co.jp/';
        static::getBody('http://auctions.yahoo.co.jp/', null);
        static::$body = static::getBody(
            $login_url . $login_params,
            'http://auctions.yahoo.co.jp/'
        );

        // get post params
        preg_match_all(
            '/document\.getElementsByName\("\.albatross"\)\[0\]\.value = "(.*?)";/',
            static::$body,
            $albatross,
            PREG_SET_ORDER
        );
        if (!$albatross[0][1])
        {
        	throw new BrowserException('Login error: albatross key not found');
        }
        preg_match_all(
            '/<input type="hidden" name="(.*?)" value="(.*?)" ?>/',
            static::$body,
            $matches,
            PREG_SET_ORDER
        );

        static::$rq->setMethod(HTTP_Request2::METHOD_POST);
        foreach ($matches as $entry) {
        	if ($entry[1] === '.nojs')
        		continue;
            static::$rq->addPostParameter($entry[1], $entry[2]);
        }
        static::$rq->addPostParameter('.albatross', $albatross[0][1]);
        static::$rq->addPostParameter('login', static::$select[0]['userid']);
        static::$rq->addPostParameter('.persistent', 'y');
        static::$rq->addPostParameter('passwd', static::$select[0]['password']);

        // need more than 3 sec before submit
        sleep(3);

        static::getBody(
            $login_url . $login_params,
            'https://login.yahoo.co.jp/config/login?'
        );

        static::$login = false;
    }

    /**
    * get response body and save cookies
    *
    * @param string $url     target url
    * @param string $referer referer url
    *
    * @return string    response body
    */
    public static function getBody($url, $referer = '')
    {
        if (empty($url))
        {
            throw new BrowserException('url can not be null');
        }
        static::$rq->setUrl($url);
        static::$rq->setHeader('Referer', $referer);

        // $s_cookies = static::$rq->getCookieJar()->serialize();

        if (!static::$login)
        {
			DB::update('yahoo')->set([
				'cookies'  => static::$rq->getCookieJar()->serialize(),
				'updated_at' => time()
			])->where('userid', Config::get('my.yahoo_user'))->execute();
        }
        return static::$rq->send()->getBody();
    }

    // Get XML body of auction
    public static function getXmlObject($auc_id = null)
    {
        if (empty($auc_id))
        {
            throw new BrowserException('auc_id can not be null');
        }

        $url = 'http://auctions.yahooapis.jp/AuctionWebService/V2/auctionItem?appid='.static::$select[0]['appid'].'&auctionID='.$auc_id;

        return simplexml_load_string(static::getBody($url));
    }

    // Test function for page of biding saved in local
    public static function getBodyBidding()
    {
        return File::read(APPPATH.'/tmp/yahoo/bidding3p.txt', true);
    }

    // Test function for page of won saved in local
    public static function getBodyWon()
    {
        return File::read(APPPATH.'/tmp/yahoo/won1p.txt', true);
    }
}
