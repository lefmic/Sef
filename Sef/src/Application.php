<?php

namespace Sef;

use Sef\Bootstrap\Bootstrap;
use Sef\Configuration\ConfigurationInterface;

/**
 * Class Application
 *
 * This is the entry of the framework
 * This class shall be instantiated by the user to start the application
 *
 * @package Sef
 */
class Application
{
    /**
     * Start the application
     *
     * @param ConfigurationInterface $configuration an instance of the configuration where the modules are defined
     */
    public function start(ConfigurationInterface $configuration)
    {
        $bootstrap = new Bootstrap();
        $bootstrap->setUp($configuration);
        $bootstrap->run();
    }
}
