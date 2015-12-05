<?php
/**
*
 */

\Autoloader::add_core_namespace('Yahoo');

\Autoloader::add_classes(array(
	'Yahoo\\Browser' => __DIR__.'/classes/browser.php',
	'Yahoo\\Parser' => __DIR__.'/classes/parser.php',
	'Yahoo\\Simple_Html_Dom' => __DIR__.'/classes/simple_html_dom.php',
	'Yahoo\\Quickstart' => __DIR__.'/classes/quickstart.php',
));
