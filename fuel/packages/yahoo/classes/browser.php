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
    private $login = false;
    private $select;
    private $auc_id;
    protected $rq;
    protected $body;

    /**
     * init function
     *
     * @return void
     */
    public function __construct($auc_id = null)
    {
        $this->auc_id = $auc_id;
    	$this->rq = new HTTP_Request2();
        $this->rq->setAdapter('curl');

    	$this->select = DB::select()->from('yahoo')->where('userid', Config::get('my.yahoo_user'))->execute()->as_array();

    	if (empty($this->select))
    	{
    		throw new BrowserException('user in config/my.yahoo_user not found in DB');
    	}
    	if ($this->select[0]['cookies'] && ($this->select[0]['updated_at'] > strtotime('-1 months')))
    	{
    		$jar = new HTTP_Request2_CookieJar();
        	$jar->unserialize($this->select[0]['cookies']);
        	$this->rq->setCookieJar($jar);
    	}
    	else
    	{
    		$this->rq->setCookieJar(true);
	        $this->rq->setHeader(
	            'User-Agent',
	            'Mozilla/6.0 (Windows; U; Windows NT 6.0; ja; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1 (.NET CLR 3.5.30729)'
	        );
	        $this->rq->setHeader('Keep-Alive', 115);
	        $this->rq->setHeader('Connection', 'keep-alive');
	        $this->login();
    	}
    }

    /**
    * login to yahoo 
    *
    */
    public function login()
    {
    	$this->login = true;

        $login_url = 'https://login.yahoo.co.jp/config/login?';
        $login_params = '.lg=jp&.intl=jp&.src=auc&.done=http://auctions.yahoo.co.jp/';
        $this->getBody('http://auctions.yahoo.co.jp/', null);
        $this->body = $this->getBody(
            $login_url . $login_params,
            'http://auctions.yahoo.co.jp/'
        );

        // get post params
        preg_match_all(
            '/document\.getElementsByName\("\.albatross"\)\[0\]\.value = "(.*?)";/',
            $this->body,
            $albatross,
            PREG_SET_ORDER
        );
        if (!$albatross[0][1])
        {
        	throw new BrowserException('Login error: albatross key not found');
        }
        preg_match_all(
            '/<input type="hidden" name="(.*?)" value="(.*?)" ?>/',
            $this->body,
            $matches,
            PREG_SET_ORDER
        );

        $this->rq->setMethod(HTTP_Request2::METHOD_POST);

        foreach ($matches as $entry)
        {
        	if ($entry[1] === '.nojs')
        		continue;
            $this->rq->addPostParameter($entry[1], $entry[2]);
        }
        $this->rq->addPostParameter('.albatross', $albatross[0][1]);
        $this->rq->addPostParameter('login', $this->select[0]['userid']);
        $this->rq->addPostParameter('.persistent', 'y');
        $this->rq->addPostParameter('passwd', $this->select[0]['password']);

        // need more than 3 sec before submit
        sleep(3);

        $this->getBody(
            $login_url . $login_params,
            'https://login.yahoo.co.jp/config/login?'
        );

        $this->login = false;
    }

    /**
    * get response body and save cookies
    *
    * @param string $url     target url
    * @param string $referer referer url
    *
    * @return string    response body
    */
    public function getBody($url, $referer = '')
    {
        if (empty($url))
        {
            throw new BrowserException('url can not be null');
        }
        $this->rq->setUrl($url);
        $this->rq->setHeader('Referer', $referer);

        if (!$this->login)
        {
			DB::update('yahoo')->set([
				'cookies'  => $this->rq->getCookieJar()->serialize(),
				'updated_at' => time()
			])->where('userid', Config::get('my.yahoo_user'))->execute();
        }
        return $this->rq->send()->getBody();
    }

    // Get XML body of auction
    public function getXmlObject()
    {
        if (empty($this->auc_id))
        {
            throw new BrowserException('auc_id can not be null');
        }

        $url = 'http://auctions.yahooapis.jp/AuctionWebService/V2/auctionItem?appid='.$this->select[0]['appid'].'&auctionID='.$this->auc_id;

        $auc_xml = simplexml_load_string($this->getBody($url));

        if ($auc_xml->Code)
        {
            if ( (int) $auc_xml->Code == 102)
            {
                throw new BrowserException('Auction not found', 102);  
            }
            else if ( (int) $auc_xml->Code == 302 )
            {
                throw new BrowserException('Auction ID is invalid', 302);
            }
        }

        return simplexml_load_string($this->getBody($url));
    }

    public function setFormValues($page_values = null, $price = 0)
    {
        $this->rq->setMethod(HTTP_Request2::METHOD_POST);
        $price_setted = false;

        Log::debug('--------------------BROWSER----------------------');
        foreach ($page_values as $value)
        {
            if ($value['name'] == 'setPrice')
            {
                if ($price < $value['value'])
                {
                    throw new BrowserException('Price must be upper or equal '.$value['value']);
                }
            }

            if ($value['name'] == 'mnewsoptin')
            {
                $value['value'] = 0;
            }

            if ($value['name'] == 'Bid')
            {
                $value['value'] = $price;
                $price_setted = true;
            }
            Log::debug($value['name']. ' - ' .$value['value']);
            $this->rq->addPostParameter($value['name'], $value['value']);

        }
        if (!$price_setted)
        {
            $this->rq->addPostParameter('Bid', $price);
        }
        Log::debug('--------------------BROWSER----------------------');
    }

    // Test function for page of biding saved in local
    public function getBodyBidding()
    {
        return File::read(APPPATH.'/tmp/yahoo/bidding3p.txt', true);
    }

    // Test function for page of won saved in local
    public function getBodyWon()
    {
        return File::read(APPPATH.'/tmp/yahoo/won1p.txt', true);
    }
}
