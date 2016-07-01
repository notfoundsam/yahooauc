<?php

class BrowserException extends Exception {}
class BrowserLoginException extends Exception {}

/**
 * Class use HTTP Requests for get HTML content from yahoo.co.jp
 * It's have Login to yahoo, bid to auction lot, get lots on bid,
 * get won lots and manage your cookie
 * 
 * @category Browser
 * @package  Yahoo
 * @author   Zazimko Alexey <notfoundsam@gmail.com>
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
     * Get cookie from DB and check them when was been last update.
     * If last update was been more than one week, get login again.
     * @return void
     */
    public function __construct()
    {
        $headers = [
            'User-Agent' => 'Mozilla/6.0 (Windows; U; Windows NT 6.0; ja; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1 (.NET CLR 3.5.30729)',
            'Keep-Alive' => 115,
            'Connection' => 'keep-alive'
        ];

        // Get cookie by Yahoo user name
        // try
        // {
        //     if ( \Cache::get('yahoo.cookies_exp') > strtotime('-1 week') )
        //     {
        //         $cookies = \Cache::get('yahoo.cookies');
        //         $this->session = new Requests_Session(self::$AUCTION_URL, $headers, [], ['cookies' => $cookies]);
        //     }
        //     else
        //     {
        //         $this->login();
        //     }
        // }
        // catch (\CacheNotFoundException $e)
        // {
        //     $this->login();
        // }

    	$this->select = DB::select()->from('yahoo')->where('userid', Config::get('my.yahoo.user_name'))->execute()->as_array();

    	if (empty($this->select))
    	{
    		throw new BrowserLoginException('User in config/my.yahoo.user_name not found in DB');
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
    * Login to yahoo auction use user name and password
    * @return void
    * @throws BrowserLoginException Throw exception if can not login
    */
    protected function login()
    {
        $this->loggedin = false;
        $this->session = new Requests_Session(self::$AUCTION_URL, $headers);

        $query = [
            '.lg' => 'jp',
            '.intl' => 'jp',
            '.src' => 'auc',
            '.done' => static::$AUCTION_URL,
        ];
        $this->getBody(static::$AUCTION_URL);
        $body = $this->getBody(static::$LOGIN_URL, $query);

        $values = Parser::getHiddenInputValues($body);

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

        $options['login']  = \Config::get('my.yahoo.user_name');
        $options['passwd'] = \Config::get('my.yahoo.user_pass');
        $options['.persistent'] = 'y';

        // Pause before submit
        sleep(3);
        $this->getBody(static::$LOGIN_URL, $query, $options, Requests::POST);
        
        // Check login
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
    * Send request to Yahoo and return body of HTML
    * @param string $url       Target url
    * @param string $query     Query parameters
    * @return string           Response body
    * @throws BrowserException Throw exception if URL for request not given
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

    /**
     * Get XML odject by auction id
     * @param  string $auc_id   Auction ID
     * @return SimpleXMLElement Return XML Object
     * @throws BrowserException Throw exception if auction ID not given
     */
    public function getXmlObject($auc_id = null)
    {
        if (empty($auc_id))
        {
            throw new BrowserException('auc_id can not be null');
        }

        $query = [
            'appid' => \Config::get('my.yahoo.user_appid'),
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

    /**
     * Create new options for request by values recived from response
     * @param  array   $page_values Array with pair name and value
     * @param  integer $price       Bidding price
     * @return array                Return options for request
     * @throws BrowserException     Throw exception if given price lower than current
     */
    protected function createRequstOptions($page_values = null, $price = 0)
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

    /**
     * Return won auction IDs from setted page
     * @param  int $page Number of page with won lots
     * @return array     Return array with won auction IDs
     */
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

    /**
     * Get lot information in bid by setted page
     * @param  int $page Number of page with lots in bid
     * @return array     Return array with lot information
     */
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

    /**
     * Bid on yahoo lot with seted price
     * @param  int $price      Price for bid
     * @param  string $auc_url URL with auction ID
     * @return bool            Reurn true if bid successful
     */
    public function bid($price = null, $auc_url = null)
    {
        $body = $this->getBody($auc_url);
        $values = Parser::getHiddenInputValues($body);

        $options = $this->createRequstOptions($values, $price);
        Log::debug('------ Browser start ------');
        Arrlog::arr_to_log($options);
        Log::debug('------- Browser end -------');

        $body = $this->getBody(static::$BID_PREVIEW, null, $options, Requests::POST);
        $values = Parser::getHiddenInputValues($body);

        $options = $this->createRequstOptions($values, $price);
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

    /**
     * Save cookies into DB after bid
     */
    function __destruct()
    {
        // 
        if ($this->loggedin)
        {
            $cookies = $this->session->options['cookies'];
            DB::update('yahoo')
            ->set([
                'cookies'  => serialize($cookies),
                'updated_at' => time()
            ])
            ->where('userid', \Config::get('my.yahoo.user_name'))
            ->execute();

            \Cache::set('yahoo.cookies', $cookies);
            \Cache::set('yahoo.cookies_exp', time());
        }
    }
}
