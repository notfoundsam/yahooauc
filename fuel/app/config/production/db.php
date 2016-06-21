<?php
/**
 * The production database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'type'        => 'mysqli',
		'connection'  => array(
			'hostname'   => getenv('OPENSHIFT_MYSQL_DB_HOST'),
			'port'       => getenv('OPENSHIFT_MYSQL_DB_PORT'),
			'database'   => 'htmlunit',
			'username'   => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
			'password'   => getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
		)
	),
);
