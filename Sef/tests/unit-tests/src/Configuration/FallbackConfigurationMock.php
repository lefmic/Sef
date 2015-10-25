<?php

namespace Mock\Module\Configuration;

use Sef\Configuration\ConfigurationInterface;

class FallbackConfigurationMock implements ConfigurationInterface
{
    /**
     * @return array
     */
    public function getConfiguration()
    {
        return array(
            'dependencies' => array(
                'Controller' => 'Mock\Module\Controller\MockController',
            ),
            'functions' => array(
                '' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (),
                ),
            ),
        );
    }
}