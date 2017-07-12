<?php

namespace DF\Opentable;

/**
 * Activation class.
 */
class Activation
{

    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Plugin activation.
     */
    public function install()
    {
        // initialise activation data.
        $this->container['license']->activation();
        flush_rewrite_rules();
    }
}
