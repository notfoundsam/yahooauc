<?php
/**
 * The production database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'type'        => 'mysqli',
		'connection'  => array(
			'hostname'   => $_SERVER['RDS_HOSTNAME'],
			'port'       => $_SERVER['RDS_PORT'] 	,
			'database'   => 'htmlunit',
			'username'   => getenv('RDS_USERNAME'),
			'password'   => getenv('RDS_PASSWORD'),
		)
	),
);
