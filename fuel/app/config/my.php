<?php

return [
	'status' => [
		'pay'      => [
			'id'       => 0,
			'name'     => 'Pay'
		],
		'paid'     => [
			'id'       => 1,
			'name'     => 'Paid'
		],
		'checking' => [
			'id'       => 5,
			'name'     => 'Checking'
		],
		'received' => [
			'id'       => 2,
			'name'     => 'Received'
		],
		'ship'     => [
			'id'       => 3,
			'name'     => 'Ship'
		],
		'shipped'     => [
			'id'       => 4,
			'name'     => 'Shipped'
		],
		'sell'     => [
			'id'       => 10,
			'name'     => 'Sell'
		],
	],

	'yahoo' => [
		'user_name'  => Input::server('YAHOO_USER'),
		'user_pass'  => Input::server('YAHOO_PASS'),
		'user_appid' => Input::server('YAHOO_APPID'),
		// Cache cookies for 1 week 3600 * 24 * 7
		'cookie_exp' => 604800,
	],

	'dropbox' => [
		'token' => Input::server('DBX_TOKEN'),
		// IMPORTANT PATH STARTS WITH "/"
		'db_path' => '/db_backup'
	],

	'task' => [
		'backup_time' => '02:00',
		// Check every $n minutes
		'lot_update_interval' => 10,
		'last_won_limit' => 250,
	],

	'main_bidder'      => 'vyacheslav',
	// 'main_bidder'      => 'sosetcadmin',
	'second_bidder'    => 'sosetcadmin',

	// Commission per item
	'commission'       => 300,

	// Cache statistic for one month
	'statistic_cache'  => 2678400,

	// Columns in pase page
	'table' => [
		'bidding'      => 8,
		'won'          => 8,
	],

	// Parse html pages from /tmp directory
	'test_mode' => [
		'enabled'      => false
	],

	'groups' => [
		'banned'        => 1,
		'guest'         => 2,
		'user'          => 3,
		'moderator'     => 4,
		'administrator' => 5,
		'superadmin'    => 6,
		'non-activated' => 7,
	],

	// rmccue request cUrl timeout
	'rmccue' => [
		'timeout' => 5,
		'connect_timeout' => 5,
		// 'verify' => false
	],
];
