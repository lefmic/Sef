<?php

namespace Mock\Module\Configuration;

use Sef\Configuration\ConfigurationInterface;

class ForumConfigurationMock implements ConfigurationInterface
{
    /**
     * @return array
     */
    public function getConfiguration()
    {
        return array(
            'dependencies' => array(
            ),
            'functions' => array(
                '' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (
                        'Controller' => 'Mock\Module\Controller\MockController',
                    ),
                ),
            ),
            'fallback' => array(
                'module' => 'Mock\Module\Configuration\FallbackConfigurationMock',
                'regexp' => ''
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfigurationWithControllerDefinedForAllFunctions()
    {
        return array(
            'dependencies' => array(
                'Controller' => 'Mock\Module\Controller\MockController'
            ),
            'functions' => array(
                '' => array(
                    'method' => 'methodToCall',
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