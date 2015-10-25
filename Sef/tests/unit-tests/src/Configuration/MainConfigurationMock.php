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
            'dependencies' => array(
                'Controller' => 'Mock\Module\Controller\MockController',
            ),
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