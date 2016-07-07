#Yahoo Auction bidder

Require php 5.4 or late 5.x version.

## Description

Yahoo auction web bidder based on fuelphp framework.
You can bid derectly on yahoo auction by lot ID.
Also it have automatic DB backup an update won lots by cron task

## Install with OpenShift

1. Clone your clean openshift project
2. Add this repo as remote repository then merge it
3. Push repo into OpenShift
4. Set OpenShift enviroment
    4.1 Set enviroment to production $ rhc env set FUEL_ENV=production -a App_Name
    4.2 Add your Yahoo account username $ rhc env set YAHOO_USER=username -a App_Name
    4.2 Add your Yahoo account password $ rhc env set YAHOO_PASS=password -a App_Name
    4.3 Add your Yahoo application ID $ rhc env set YAHOO_APPID=key -a App_Name
    4.4 Add your Dropbox token $ rhc env set DBX_TOKEN=key -a App_Name
5. Go to OpenShift ssh (rhc ssh APP_NAME from command line)
6. Go to app-root/runtime/repo/ and run composer manually ( ./composer.phar install ) 
   When you asked for Token enter your token from github.com (If you do not have yet create one)
7. Add new user for bid.
    7.1 In same folder run fuelphp oil console $ php oil console
    7.2 Create new user $ Auth::create_user('username', 'password', 'your@email.com')
    7.3 Do not foget set this username to fuel/app/config/my.php field main_bidder
9. Restart your application