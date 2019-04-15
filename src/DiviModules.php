<?php
namespace DF\Opentable;

/**
 * Register divi modules
 */
class DiviModules
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function enqueue_divi_module_scripts()
    {
        wp_enqueue_script(
            'df-opentable-script',
            $this->container['plugin_url'] . '/resources/js/scripts.js',
            ['jquery']
        );
    }

    public function frontend_builder_enqueue_scripts()
    {
        if (isset($_GET['et_fb']) and ($_GET['et_fb'] == '1')) {
            wp_enqueue_script('magnific-popup', ET_BUILDER_URI . '/scripts/jquery.magnific-popup.js', ['jquery'], ET_BUILDER_VERSION, true);
            wp_enqueue_style('magnific-popup', ET_BUILDER_URI . '/styles/magnific_popup.css', [], ET_BUILDER_VERSION);
            $this->enqueue_divi_module_scripts();
        }
    }

    /**
     * Register divi modules.
     */
    public function register()
    {
        new \Opentable_Divi_Modules\OpentableWidgetModule\OpentableWidgetModule($this->container);
    }

    public function register_extensions()
    {
        new \Opentable_Divi_Modules\OpentableWidgetExtension($this->container);
    }

    public function wp_print_styles()
    {
        // divi frontend builder styles.
        wp_dequeue_style('et_pb_df_opentable_widget-styles');
    }
}
