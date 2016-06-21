<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2015 Fuel Development Team
 * @link       http://fuelphp.com
 */

\Autoloader::add_core_namespace('Yahoo');

\Autoloader::add_classes(array(
	'Yahoo\\Browser' => __DIR__.'/classes/browser.php',
	'Yahoo\\Parser' => __DIR__.'/classes/parser.php',
	'Yahoo\\Arrlog' => __DIR__.'/classes/arrlog.php',
	'Yahoo\\Dropbox' => __DIR__.'/classes/dropbox.php',
));
