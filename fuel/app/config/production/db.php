<?php
/**
 * The production database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host='.getenv('OPENSHIFT_MYSQL_DB_HOST').':'.getenv('OPENSHIFT_MYSQL_DB_PORT').';dbname=fuel_dev4',
			'username'   => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
			'password'   => getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
		),
		'profiling' => false
	),
);
