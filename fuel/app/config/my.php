<?php

return [
	'status' => [
		'pay'      	   => 0,
		'paid'     	   => 1,
		'received' 	   => 2,
		'ship'     	   => 3,
		'sell'     	   => 10,
	],

	// 'yahoo_user'       => 'pekopeko_haraheringu',
	'yahoo_user'       => 'notfoundsam',

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
		'enabled'      => false,
		'bidding_page' => APPPATH.'/tmp/yahoo/strong_bid.txt',
		'won_page'     => APPPATH.'/tmp/yahoo/won.txt',
		'result_page'  => APPPATH.'/tmp/yahoo/success.txt',
	],
];

