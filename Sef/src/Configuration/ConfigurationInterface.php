<?php

namespace Sef\Configuration;

/**
 * Interface ConfigurationInterface
 *
 * Shall be used for all configuration files that will be used by this framework
 *
 * @package Sef\Configuration
 */
interface ConfigurationInterface
{
    /**
     * @return array
     */
    public function getConfiguration();
}
