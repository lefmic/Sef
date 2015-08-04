<?php

namespace Sef\Router;

use Sef\Configuration\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    // @codeCoverageIgnoreStart

    /**
     * contains the module configuration
     * @var array $module
     */
    private $moduleConfiguration;

    /**
     * @var Request $request
     */
    private $request;

    /**
     * @var string
     */
    private $moduleString = null;

    /**
     * @var string
     */
    private $pathString = null;

    /**
     * @param array $moduleConfiguration
     */
    public function setModuleConfiguration($moduleConfiguration)
    {
        $this->moduleConfiguration = $moduleConfiguration;
    }

    /**
     * @return array
     */
    public function getModuleConfiguration()
    {
        return $this->moduleConfiguration;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $moduleString
     */
    public function setModuleString($moduleString)
    {
        $this->moduleString = $moduleString;
    }

    /**
     * @return string
     */
    public function getModuleString()
    {
        return $this->moduleString;
    }

    /**
     * @param string $pathString
     */
    public function setPathString($pathString)
    {
        $this->pathString = $pathString;
    }

    /**
     * @return string
     */
    public function getPathString()
    {
        return $this->pathString;
    }

    // @codeCoverageIgnoreEnd

    /**
     * expects an array of modules
     * key: module
     * value: moduleConfigurationNamespace
     *
     * @param array $configuration
     * @throws \Exception
     */
    public function process(array $configuration)
    {
        $this->resolvePath();
        if (false === $this->resolveModule($configuration)) {
            /**
             * @var ConfigurationInterface $moduleConfiguration
             */
            $moduleConfiguration = new $configuration['Main']();
        } else {
            /**
             * @var ConfigurationInterface $moduleConfiguration
             */
            $moduleConfiguration = new $configuration[ucfirst($this->moduleString)]();
        }
        $this->moduleConfiguration = $moduleConfiguration->getConfiguration();
    }

    /**
     * resolve the path and the module
     */
    protected function resolvePath()
    {
        $requestUri = trim($this->request->getRequestUri(), '/');
        $urlArr = explode('/', $requestUri, 2);
        $this->moduleString = $urlArr[0];
        $this->pathString = isset($urlArr[1]) ? $urlArr[1] : null;
    }

    /**
     * check whether the desired module exists in the configuration
     *
     * @param array $modulesArray
     * @return bool
     * @throws \Exception
     */
    protected function resolveModule(array $modulesArray)
    {
        if (!array_key_exists(ucfirst($this->moduleString), $modulesArray)) {
            if (!array_key_exists('Main', $modulesArray)) {
                throw new \Exception('Missing fallback module \"Main\"');
            }
            return false;
        }
        return true;
    }
}
