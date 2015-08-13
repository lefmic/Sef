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
            'dependencies' => array(
                'Controller' => '',
            ),
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
            'dependencies' => array(
                'Controller' => \DI\object('namespace\to\main\controller')->lazy(),
            ),
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
            'dependencies' => array(
                'Controller' => \DI\object('namespace\to\main\controller')->lazy(),
            ),
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
        // TODO: Do nothing this is a bad configuration
    }
}