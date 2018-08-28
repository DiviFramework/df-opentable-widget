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

    /**
     * Register divi modules.
     */
    public function register()
    {
        new \Opentable_Divi_Modules\OpentableWidgetModule\OpentableWidgetModule($this->container);
    }

    public function register_extensions(){
        new \Opentable_Divi_Modules\OpentableWidgetExtension($this->container);
    }

    public function wp_print_styles(){
        // divi frontend builder styles. 
        wp_dequeue_style( 'et_pb_df_opentable_widget-styles' );
    }
}
