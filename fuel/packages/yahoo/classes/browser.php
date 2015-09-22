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

    	if (File::exists(APPPATH.'/tmp/yahoo/cookies.txt'))
        {
        	$cookies = File::read(APPPATH.'/tmp/yahoo/cookies.txt', true);
        
        	$jar = new HTTP_Request2_CookieJar();
        	$jar->unserialize($cookies);
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

    	$select = DB::select()->from('yahoo')->where('id', Config::get('my.yahoo_user'))->execute();

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
        static::$rq->addPostParameter('login', $select[0]['userid']);
        static::$rq->addPostParameter('.persistent', 'y');
        static::$rq->addPostParameter('passwd', $select[0]['password']);

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
        if (empty($url)) {
            return null;
        }
        static::$rq->setUrl($url);
        static::$rq->setHeader('Referer', $referer);

        $s_cookies = static::$rq->getCookieJar()->serialize();

        if (!static::$login)
        {
            if (File::exists(APPPATH.'/tmp/yahoo/cookies.txt'))
            {
            	File::update(APPPATH.'/tmp/yahoo/', 'cookies.txt', static::$rq->getCookieJar()->serialize());
            }
            else
            {
            	File::create(APPPATH.'/tmp/yahoo/', 'cookies.txt', static::$rq->getCookieJar()->serialize());
            }
        }
        return static::$rq->send()->getBody();
    }

    public static function getBodyBidding()
    {
        return File::read(APPPATH.'/tmp/yahoo/bidding.txt', true);
    }

    public static function getBodyWon()
    {
        return File::read(APPPATH.'/tmp/yahoo/won.txt', true);
    }
}
