<?php
/**
 * Plugin Name:     DF Opentable Widget Module
 * Plugin URI:      https://www.diviframework.com
 * Description:     Divi module for Opentable Widget
 * Author:          Divi Framework
 * Author URI:      https://www.diviframework.com
 * Text Domain:     df-opentable-widget
 * Domain Path:     /languages
 * Version:         1.0.2
 *
 * @package
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('DF_OPENTABLE_VERSION', '1.0.2');
define('DF_OPENTABLE_DIR', __DIR__);
define('DF_OPENTABLE_URL', plugins_url('/' . basename(__DIR__)));

require_once DF_OPENTABLE_DIR . '/vendor/autoload.php';

$container = new \DF\Opentable\Container;
$container['plugin_name'] = 'DF Opentable Widget Module';
$container['plugin_version'] = DF_OPENTABLE_VERSION;
$container['plugin_file'] = __FILE__;
$container['plugin_dir'] = DF_OPENTABLE_DIR;
$container['plugin_url'] = DF_OPENTABLE_URL;
$container['plugin_slug'] = 'df-opentable-widget';

//register API license checks.
$container->registerLicense();

// activation hook.
register_activation_hook(__FILE__, array($container['activation'], 'install'));

$container->run();
