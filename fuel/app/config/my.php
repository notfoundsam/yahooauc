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
		'received' => [
			'id'       => 2,
			'name'     => 'Received'
		],
		'ship'     => [
			'id'       => 3,
			'name'     => 'Ship'
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

	// Columns in pase page
	'table' => [
		'bidding'      => 7,
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
	]
];
