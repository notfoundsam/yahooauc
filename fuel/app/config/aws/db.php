<?php
/**
 * The production database settings. These get merged with the global settings.
 */
return array(
	'default' => array(
		'type'        => 'mysqli',
		'connection'  => array(
			'hostname'   => getenv('RDS_HOSTNAME'),
			'port'       => getenv('RDS_PORT'),
			'database'   => getenv('RDS_DB_NAME'),
			'username'   => getenv('RDS_USERNAME'),
			'password'   => getenv('RDS_PASSWORD'),
		)
	),
);
