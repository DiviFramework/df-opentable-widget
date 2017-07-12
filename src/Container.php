<?php
namespace DF\Opentable;

use Pimple\Container as PimpleContainer;

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
    }

    /**
     * Start the plugin
     */
    public function run()
    {
        // divi module register.
        add_action('et_builder_ready', array($this['divi_modules'], 'register'), 1);

        // check for dependancies
        add_action('plugins_loaded', array($this['themes'], 'checkDependancies'));
        add_action('admin_head', array($this, 'flushLocalStorage'));
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
     * Register license.
     */
    public function registerLicense()
    {
        // License check.
        // License setup.
        // Load the API Key library if it is not already loaded. Must be placed in the root plugin file.
        if (!class_exists('AM_License_Menu')) {
            require_once $this['plugin_dir'] . '/am-license-menu.php';
        }

        /**
         * @param string $file             Must be __FILE__ from the root plugin file, or theme functions file.
         * @param string $software_title   Must be exactly the same as the Software Title in the product.
         * @param string $software_version This product's current software version.
         * @param string $plugin_or_theme  'plugin' or 'theme'
         * @param string $api_url          The URL to the site that is running the API Manager. Example: https://www.toddlahman.com/
         *
         * @return \AM_License_Submenu|null
         */
        $license = new \AM_License_Menu($this['plugin_file'], $this['plugin_name'], $this['plugin_version'], 'plugin', 'https://www.diviframework.com/', '', '');

        $this['license'] = $license;

        return $license;
    }
}
