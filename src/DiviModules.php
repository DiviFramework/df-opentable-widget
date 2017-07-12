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
        new OpentableWidgetModule($this->container);
    }
}
