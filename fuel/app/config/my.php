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

	// 'yahoo_user'       => 'pekopeko_haraheringu',
	'yahoo_user'       => 'notfoundsam',
	// 'yahoo' => [
	// 	'user_name'  => Input::server('YAHOO_USER'),
	// 	'user_pass'  => Input::server('YAHOO_PASS'),
	// 	'user_appid' => Input::server('YAHOO_APPID'),
	// ],

	'main_bidder'      => 'vyacheslav',
	// 'main_bidder'      => 'sosetcadmin',
	'second_bidder'    => 'sosetcadmin',

	// Limit of auctions getting from DB
	'limit'            => 250,

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
		'banned' => 1,
		'guest' => 2,
		'user' => 3,
		'moderator' => 4,
		'administrator' => 5,
		'superadmin' => 6,
		'non-activated' => 7,
	]
];

