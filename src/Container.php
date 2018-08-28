<?php
namespace DF\Opentable;

use DiviFramework\UpdateChecker\PluginLicense;
use Pimple\Container as PimpleContainer;

/**
 * DI Container.
 */
class Container extends PimpleContainer {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->initObjects();
	}

	/**
	 * Define dependancies.
	 */
	public function initObjects() {
		$this['activation'] = function ($container) {
			return new Activation($container);
		};

		$this['divi_modules'] = function ($container) {
			return new DiviModules($container);
		};

		$this['themes'] = function ($container) {
			return new Themes($container);
		};

		$this['license'] = function ($container) {
			return new PluginLicense($container, 'https://www.diviframework.com');
		};

	}

	/**
	 * Start the plugin
	 */
	public function run() {
		$this['license']->init(); // license init in plugin run.
		// divi module register.
		add_action('et_builder_ready', array($this['divi_modules'], 'register'), 1);
		add_action( 'divi_extensions_init', [$this['divi_modules'], 'register_extensions'] );

		// check for dependancies
		add_action('plugins_loaded', array($this['themes'], 'checkDependancies'));
		add_action('admin_head', array($this, 'flushLocalStorage'));

		// remove divi frontend builder styles since we don't want them.
		add_action( 'wp_print_styles', [$this['divi_modules'], 'wp_print_styles'] );
	}

	/**
	 * Flush local storage items.
	 *
	 * @return [type] [description]
	 */
	public function flushLocalStorage() {
		echo "<script>" .
			"localStorage.removeItem('et_pb_templates_et_pb_df_opentable_widget');" .
			"</script>";
	}
}
