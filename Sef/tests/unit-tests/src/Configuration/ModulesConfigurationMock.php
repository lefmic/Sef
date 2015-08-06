<?php

namespace Mock\Module\Configuration;

use Sef\Configuration\ConfigurationInterface;

class ModulesConfigurationMock implements ConfigurationInterface
{
    /**
     * @return array
     */
    public function getConfiguration()
    {
        return array(
            'modules' => array(
                'Main' => 'Mock\Module\Configuration\MainConfigurationMock',
                'Forum' => 'Mock\Module\Configuration\ForumConfigurationMock',
            ),
            'fallback' => array(
                'module' => 'Mock\Module\Configuration\FallbackConfigurationMock',
                'regexp' => ''
            )
        );
    }

    public function getNoModulesConfiguration()
    {
        return array(
            'fallback' => array(
                'module' => 'Mock\Module\Configuration\MainConfigurationMock',
                'regexp' => ''
            )
        );
    }

    public function getEmptyModulesConfiguration()
    {
        return array(
            'modules' => array(),
            'fallback' => array(
                'module' => 'Mock\Module\Configuration\FallbackConfigurationMock',
                'regexp' => ''
            )
        );
    }

    public function getNoFallbackConfiguration()
    {
        return array(
            'modules' => array(
                'Forum' => 'Mock\Module\Configuration\ForumConfigurationMock',
            ),
        );
    }

    public function getEmptyFallbackConfiguration()
    {
        return array(
            'modules' => array(
                'Forum' => 'Mock\Module\Configuration\ForumConfigurationMock',
            ),
            'fallback' => array()
        );
    }
}