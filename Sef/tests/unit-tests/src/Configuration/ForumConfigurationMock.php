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
            'controller' => 'Mock\Module\Controller\MockController',
            'dependencies' => array(),
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