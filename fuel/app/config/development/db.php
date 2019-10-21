<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'type'        => 'mysqli',
		'connection'  => array(
			'hostname'   => 'db',
			'port'       => '3306',
			'database'   => 'ebdb',
			'username'   => 'root',
			'password'   => 'root',
		),
		'profiling' => true
	),
);
