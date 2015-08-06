<?php

namespace Mock\Module\Configuration;

use Sef\Configuration\ConfigurationInterface;

class BadConfigurationMock implements ConfigurationInterface
{
    /**
     * @return array
     */
    public function getConfigurationNoController()
    {
        return array(
            'dependencies' => array(),
            'functions' => array(
                'regexp\/for\/the\/path\/?' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (),
                ),
            ),
            'fallback' => array(
                'module' => 'namespace\to\fallback\module\controller',
                'regexp' => ''
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfigurationEmptyController()
    {
        return array(
            'controller' => '',
            'dependencies' => array(),
            'functions' => array(
                'regexp\/for\/the\/path\/?' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (),
                ),
            ),
            'fallback' => array(
                'module' => 'namespace\to\fallback\module\controller',
                'regexp' => ''
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfigurationNoFallback()
    {
        return array(
            'controller' => 'namespace\to\main\controller',
            'dependencies' => array(),
            'functions' => array(
                'regexp\/for\/the\/path\/?' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (),
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfigurationEmptyFallback()
    {
        return array(
            'controller' => 'namespace\to\main\controller',
            'dependencies' => array(),
            'functions' => array(
                'regexp\/for\/the\/path\/?' => array(
                    'method' => 'methodToCall',
                    'dependencies' => array (),
                ),
            ),
            'fallback' => array(),
        );
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        // TODO: Do Nothing this is a bad configuration
    }
}