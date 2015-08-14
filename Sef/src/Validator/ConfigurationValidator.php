<?php

namespace Sef\Validator;

/**
 * Class ConfigurationValidator
 *
 * Validate the given configurations
 *
 * @package Sef\Validator
 */
class ConfigurationValidator
{
    /**
     * check the configuration file for all modules
     *
     * @param array $configuration
     * @throws \Exception
     */
    public function validateModulesConfiguration(array $configuration)
    {
        if (!array_key_exists('modules', $configuration) || empty($configuration['modules'])) {
            throw new \Exception('Configuration has no modules defined');
        }
        if (!array_key_exists('fallback', $configuration) || empty($configuration['fallback'])) {
            throw new \Exception('Configuration has no fallback defined');
        }
        // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * validate the explicit module configuration
     *
     * @param array $configuration
     * @param null $regexp
     * @param bool|false $fallback
     * @throws \Exception
     */
    public function validateModuleConfiguration(array $configuration, $fallback = false, $regexp = null)
    {
        if (
            null !== $regexp &&
            (!array_key_exists('Controller', $configuration['functions'][$regexp]['dependencies']) ||
                empty($configuration['functions'][$regexp]['dependencies']['Controller']))
        ) {
            throw new \Exception('No Controller defined');
        }
        if (
            (false === $fallback) &&
            (!array_key_exists('fallback', $configuration) ||
                empty($configuration['fallback']))) {
            throw new \Exception('No Fallback defined');
        }
        // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd
}