<?php

namespace Fuel\Tasks;
use \Dropbox as dbx;

/**
* 
*/
class Dropbox
{
	private static $_TOKEN = 'v31qPdZCfWoAAAAAAAAmG-R0vMVI7FxwSAtN7FN2uhE_QPDlib6lw9KXnPiZAc8S';

	public static function run()
	{
		$appInfo = dbx\AppInfo::loadFromJsonFile(APPPATH.'/tmp/yahoo/dropbox.json');
		$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

		$authorizeUrl = $webAuth->start();

		echo "1. Go to: " . $authorizeUrl . "\n";
		echo "2. Click \"Allow\" (you might have to log in first).\n";
		echo "3. Copy the authorization code.\n";
		$authCode = \trim(\readline("Enter the authorization code here: "));

		list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
		print "Access Token: " . $accessToken . "\n";

		$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
		$accountInfo = $dbxClient->getAccountInfo();
		print_r($accountInfo);
	}

	public static function loadFile()
	{
		$dbxClient = new dbx\Client(static::$_TOKEN, "PHP-Example/1.0");
		// $accountInfo = $dbxClient->getAccountInfo();
		$f = fopen(APPPATH.'/tmp/yahoo/bid.txt', "rb");
		$result = $dbxClient->uploadFile("/bid.txt", dbx\WriteMode::add(), $f);
		fclose($f);
		print_r($result);
	}

	public static function shareFile()
	{
		$dbxClient = new dbx\Client(static::$_TOKEN, "PHP-Example/1.0");
		$link = $dbxClient->createTemporaryDirectLink("/mar.jpg");

		print_r($link);
	}
}