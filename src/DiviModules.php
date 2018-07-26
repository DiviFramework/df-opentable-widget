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
}
