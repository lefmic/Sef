<?php

namespace Sef;

use Sef\Bootstrap\Bootstrap;
use Sef\Configuration\ApplicationConfiguration;
use Sef\Configuration\ConfigurationInterface;

class Application
{
    /**
     * kick off the application
     *
     * @param ConfigurationInterface $configuration
     */
    public function start(ConfigurationInterface $configuration)
    {
        $bootstrap = new Bootstrap();
        $bootstrap->setUp($configuration);
        $bootstrap->run();
    }
}
