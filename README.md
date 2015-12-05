#FuelPHP

php 5.4 or late!!!

## Description

FuelPHP is a fast, lightweight PHP 5.3 framework. In an age where frameworks are a dime a dozen, We believe that FuelPHP will stand out in the crowd.  It will do this by combining all the things you love about the great frameworks out there, while getting rid of the bad.

## Install with OpenShift

1. Clone your clean openshift project
2. Add this repo as remote repository then merge it
3. Push repo into OpenShift
4. Set OpenShift enviroment $ rhc env set FUEL_ENV=production -a App_Name
5. Go to OpenShift ssh (rhc ssh APP_NAME from command line)
6. Go to app-root/runtime/repo/ and run composer manually ( ./composer.phar update ) 
7. When you asked for Token enter your token from github.com (If you do not have yet create one)
8. Go to php/phplib/pear/pear/php/HTTP/Request2/ and edit file CookieJar.php on line 444 like $path = getenv('OPENSHIFT_HOMEDIR').'/php/phplib/pear/pear/data' . DIRECTORY_SEPARATOR . 'HTTP_Request2';
Also update method:
public function addCookiesFromResponse(HTTP_Request2_Response $response, Net_URL2 $setter = null)
    {
        if (null === $setter) {
            if (!($effectiveUrl = $response->getEffectiveUrl())) {
                throw new HTTP_Request2_LogicException(
                    'Response URL required for adding cookies from response',
                    HTTP_Request2_Exception::MISSING_VALUE
                );
            }
            $setter = new Net_URL2($effectiveUrl);
        }
        $success = true;
        foreach ($response->getCookies() as $cookie) {
            $success = $this->store($cookie, $setter) && $success;
        }
        return $success;
    }
9. Restart your application