<?php
namespace DF\Opentable;

use Pimple\Container as PimpleContainer;
use DiviFramework\UpdateChecker\PluginLicense;

/**
 * DI Container.
 */
class Container extends PimpleContainer
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initObjects();
    }

    /**
     * Flush local storage items.
     *
     * @return [type] [description]
     */
    public function flushLocalStorage()
    {
        echo "<script>" .
            "localStorage.removeItem('et_pb_templates_et_pb_df_opentable_widget');" .
            "</script>";
    }

    /**
     * Define dependancies.
     */
    public function initObjects()
    {
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
    public function run()
    {
        $this['license']->init(); // license init in plugin run.
        // divi module register.
        add_action('et_builder_ready', [$this['divi_modules'], 'register'], 1);
        add_action('divi_extensions_init', [$this['divi_modules'], 'register_extensions']);

        // check for dependancies
        add_action('plugins_loaded', [$this['themes'], 'checkDependancies']);
        add_action('admin_head', [$this, 'flushLocalStorage']);

        // remove divi frontend builder styles since we don't want them.
        add_action('wp_print_styles', [$this['divi_modules'], 'wp_print_styles']);
        add_action('wp_enqueue_scripts', [$this['divi_modules'], 'frontend_builder_enqueue_scripts']);
    }
}
