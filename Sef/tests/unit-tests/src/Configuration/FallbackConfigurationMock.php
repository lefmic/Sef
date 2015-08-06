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
            'controller' => 'Mock\Module\Controller\MockController',
            'dependencies' => array(),
            'functions' => array(
                '' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (),
                ),
            ),
        );
    }
}