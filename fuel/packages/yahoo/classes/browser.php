<?php
// use Fuel\Log;
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
// require_once 'HTTP/Request2.php';
// require_once 'HTTP/Request2/CookieJar.php';

class BrowserException extends Exception {}
class BrowserLoginException extends Exception {}

/**
 * Class login and scraping your yahoo.co.jp pages
 *
 * @category Grab
 * @package  Yahoo
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
 * @license  
 * @link     https://github.com/notfoundsam/yahooauc
 */
class Browser
{
    protected static $AUCTION_URL  = 'http://auctions.yahoo.co.jp/';
    protected static $LOGIN_URL    = 'https://login.yahoo.co.jp/config/login';
    protected static $CLOSED_USER  = 'http://closeduser.auctions.yahoo.co.jp/jp/show/mystatus';
    protected static $OPEN_USER    = 'http://openuser.auctions.yahoo.co.jp/jp/show/mystatus';
    protected static $BID_PREVIEW  = 'http://auctions.yahoo.co.jp/jp/show/bid_preview';
    protected static $PLACE_BID    = 'http://auctions.yahoo.co.jp/jp/config/placebid';
    protected static $API_URL      = 'http://auctions.yahooapis.jp/AuctionWebService/V2/auctionItem';

    protected $loggedin            = true;
    protected $select              = null;
    protected $session             = null;

    /**
     * init function
     *
     * @return void
     */
    public function __construct()
    {
        $headers = [
            'User-Agent' => 'Mozilla/6.0 (Windows; U; Windows NT 6.0; ja; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1 (.NET CLR 3.5.30729)',
            'Keep-Alive' => 115,
            'Connection' => 'keep-alive'
        ];
        // Need to optimize select, separete pass, user, cookies e.g.
    	$this->select = DB::select()->from('yahoo')->where('userid', Config::get('my.yahoo_user'))->execute()->as_array();

    	if (empty($this->select))
    	{
    		throw new BrowserLoginException('User in config/my.yahoo_user not found in DB');
    	}

    	if ($this->select[0]['cookies'] && ($this->select[0]['updated_at'] > strtotime('-1 week')))
    	{
            $cookies = unserialize($this->select[0]['cookies']);
            $this->session = new Requests_Session(self::$AUCTION_URL, $headers, [], ['cookies' => $cookies]);
    	}
    	else
    	{
            $this->session = new Requests_Session(self::$AUCTION_URL, $headers);
            $this->loggedin = false;
	        $this->login();
    	}
    }

    /**
    * Login to yahoo 
    *
    */
    protected function login()
    {
        $query = [
            '.lg' => 'jp',
            '.intl' => 'jp',
            '.src' => 'auc',
            '.done' => static::$AUCTION_URL,
        ];
        $this->getBody(static::$AUCTION_URL);
        $body = $this->getBody(static::$LOGIN_URL, $query);

        $values = Parser::getAuctionPageValues($body);

        preg_match_all(
            '/document\.getElementsByName\("\.albatross"\)\[0\]\.value = "(.*?)";/',
            $body,
            $albatross,
            PREG_SET_ORDER
        );

        if (!$albatross[0][1])
        {
            throw new BrowserLoginException('Albatross key not found');
        }
        
        $options = [];
        foreach ($values as $value)
        {
            if ($value['name'] == '.nojs')
            {
                continue;
            }

            if ($value['name'] == '.albatross')
            {
                $value['value'] = $albatross[0][1];
            }

            $options[$value['name']] = $value['value'];
        }

        $options['login'] = $this->select[0]['userid'];
        $options['passwd'] = $this->select[0]['password'];
        $options['.persistent'] = 'y';

        // Pause before submit
        sleep(3);
        $this->getBody(static::$LOGIN_URL, $query, $options, Requests::POST);
        
        // Check by login
        $body = $this->getBody(static::$AUCTION_URL);

        if (Parser::checkLogin($body))
        {
            $this->loggedin = true;
        }
        else
        {
            throw new BrowserLoginException('Could not login into yahoo');
        }
    }

    /**
    * Get response body
    *
    * @param string $url     target url
    * @param string $query   query parameters
    *
    * @return string         response body
    */
    protected function getBody($url = null, $query = null, $options = [], $method = Requests::GET)
    {
        if (empty($url))
        {
            throw new BrowserException('url can not be null');
        }
        
        $request_uri = $query ? $url . '?' . http_build_query($query) : $url;
        Log::debug('getBody: '. $request_uri);
        $response = $this->session->request($request_uri, [], $options, $method);

        return $response->body;
    }

    // Get XML odject by auction id
    public function getXmlObject($auc_id = null)
    {
        if (empty($auc_id))
        {
            throw new BrowserException('auc_id can not be null');
        }

        $query = [
            'appid' => $this->select[0]['appid'],
            'auctionID' => $auc_id,
        ];

        $body = $this->getBody(static::$API_URL, $query);
        $auc_xml = simplexml_load_string($body);

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

        return $auc_xml;
    }

    protected function createFormValues($page_values = null, $price = 0)
    {
        $options = [];
        $price_setted = false;

        foreach ($page_values as $value)
        {
            if(!$value['name'])
                continue;

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
            $options[$value['name']] = $value['value'];
        }

        if (!$price_setted)
        {
            $options['Bid'] = $price;
        }

        $options['Quantity'] = 1;

        return $options;
    }

    public function won($page = null)
    {
        $query = [
            'select'  => 'won',
            'picsnum' => '50',
            'apg'     => $page ? $page : 1
        ];

        if (\Config::get('my.test_mode.enabled'))
        {
            $body = $this->getBodyWon();
        }
        else
        {
            $body = $this->getBody(static::$CLOSED_USER, $query);
        }

        $ids = Parser::parseWonPageNew($body);

        return $ids;
    }

    public function bidding($page = null)
    {
         $query = [
            'select'  => 'bidding',
            'picsnum' => '50',
            'apg'     => $page ? $page : 1
        ];

        if (\Config::get('my.test_mode.enabled'))
        {
            $body = $this->getBodyBidding();
        }
        else
        {
            $body = $this->getBody(static::$OPEN_USER, $query);
        }

        $table = Parser::parseBiddingPageNew($body);
        
        return $table; 
    }

    public function bid($auc_id = null, $price = null, $auc_url = null)
    {
        $body = $this->getBody($auc_url);
        $values = Parser::getAuctionPageValues($body);

        $options = $this->createFormValues($values, $price);
        Log::debug('------ Browser start ------');
        Arrlog::arr_to_log($options);
        Log::debug('------- Browser end -------');

        $body = $this->getBody(static::$BID_PREVIEW, null, $options, Requests::POST);
        $values = Parser::getAuctionPageValues($body);

        $options = $this->createFormValues($values, $price);
        Log::debug('------ Browser start ------');
        Arrlog::arr_to_log($options);
        Log::debug('------- Browser end -------');

        if (\Config::get('my.test_mode.enabled'))
        {
            $body = $this->getResultPage();
        }
        else
        {
            $body = $this->getBody(static::$PLACE_BID, null, $options, Requests::POST);
        }
        
        $result = Parser::getResult($body);

        return $result;
    }

    // Test function for page of biding saved in local
    public function getBodyBidding()
    {
        return File::read(\Config::get('my.test_mode.bidding_page'), true);
    }

    // Test function for page of won saved in local
    public function getBodyWon()
    {
        return File::read(\Config::get('my.test_mode.won_page'), true);
    }

    // Test function for result page of bid saved in local
    public function getResultPage()
    {
        return File::read(\Config::get('my.test_mode.result_page'), true);
    }

    function __destruct()
    {
        // Save cookies into DB
        if ($this->loggedin)
        {
            $cookies = $this->session->options['cookies'];
            DB::update('yahoo')
            ->set([
                'cookies'  => serialize($cookies),
                'updated_at' => time()
            ])
            ->where('userid', Config::get('my.yahoo_user'))
            ->execute();
        }
    }
}
