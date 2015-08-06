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
            'controller' => 'Mock\Module\Controller\MockController',
            'dependencies' => array(),
            'functions' => array(
                'regexp\/for\/the\/path\/?' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (),
                ),
                'regexp\/for\/another\/path\/?' => array(
                    'method' => 'anotherMethodToCall',
                    'dependencies' => array (),
                ),
            ),
            'fallback' => array(
                'module' => 'Mock\Module\Configuration\FallbackConfigurationMock',
                'regexp' => ''
            ),
        );
    }
}