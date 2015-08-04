<?php

namespace Mock\Module\Configuration;

use Sef\Configuration\ConfigurationInterface;

class MainConfigurationMock implements ConfigurationInterface
{
    /**
     * @return array
     */
    public function getConfiguration()
    {
        return array(
            'Controller' => 'MainController'
        );
    }
}