<?php

namespace Sef\Validator;

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
     * @param bool $fallback
     * @throws \Exception
     */
    public function validateModuleConfiguration(array $configuration, $fallback = false)
    {
        if (!array_key_exists('controller', $configuration) || empty($configuration['controller'])) {
            throw new \Exception('No Controller defined');
        }
        if (
            (false !== $fallback) &&
            (!array_key_exists('fallback', $configuration) ||
            empty($configuration['fallback']))) {
            throw new \Exception('No Fallback defined');
        }
        // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd
}