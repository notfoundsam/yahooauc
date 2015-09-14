<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
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
