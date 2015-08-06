<?php

namespace Sef\Router;

use Sef\Configuration\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    // @codeCoverageIgnoreStart

    /**
     * @var array
     */
    private $modules;

    /**
     * contains the module configuration
     * @var array $module
     */
    private $moduleConfiguration;

    /**
     * @var string
     */
    private $matchingRegexp;

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
     * @var bool
     */
    private $moduleIsFallback = false;

    /**
     * @param array $modules
     */
    public function setModules($modules)
    {
        $this->modules = $modules;
    }

    /**
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

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
     * @param string $matchingRegexp
     */
    public function setMatchingRegexp($matchingRegexp)
    {
        $this->matchingRegexp = $matchingRegexp;
    }

    /**
     * @return string
     */
    public function getMatchingRegexp()
    {
        return $this->matchingRegexp;
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

    /**
     * @param boolean $moduleIsFallback
     */
    public function setModuleIsFallback($moduleIsFallback)
    {
        $this->moduleIsFallback = $moduleIsFallback;
    }

    /**
     * @return boolean
     */
    public function getModuleIsFallback()
    {
        return $this->moduleIsFallback;
    }

    // @codeCoverageIgnoreEnd

    /**
     * resolve the path and the module
     */
    public function resolvePath()
    {
        $requestUri = trim($this->request->getRequestUri(), '/');
        $urlArr = explode('/', $requestUri, 2);
        $this->moduleString = $urlArr[0];
        $this->pathString = isset($urlArr[1]) ? $urlArr[1] : null;
    }

    /**
     * check whether the desired module exists in the configuration
     */
    public function resolveModule()
    {
        if (!array_key_exists(ucfirst($this->moduleString), $this->modules['modules'])) {
            $moduleConf = $this->modules['fallback']['module'];
        } else {
            $moduleConf = $this->modules['modules'][ucfirst($this->moduleString)];
        }
        /**
         * @var ConfigurationInterface $moduleConfiguration
         */
        $moduleConfiguration = new $moduleConf();
        $this->moduleConfiguration = $moduleConfiguration->getConfiguration();
    }

    /**
     * expects an array of modules
     * key: module
     * value: moduleConfigurationNamespace
     *
     * @throws \Exception
     */
    public function process()
    {
        $regexp = $this->resolveRegExp($this->moduleConfiguration);
        if (false === $regexp) {
            $moduleConf = $this->moduleConfiguration['fallback']['module'];
            $this->matchingRegexp = $this->moduleConfiguration['fallback']['regexp'];
            /**
             * @var ConfigurationInterface $moduleConfiguration
             */
            $moduleConfiguration = new $moduleConf();
            $this->moduleConfiguration = $moduleConfiguration->getConfiguration();
            $this->moduleIsFallback = true;
        } else {
            $this->matchingRegexp = $regexp;
        }
    }

    /**
     * find a matching regex for the path in the given configuration or return false
     *
     * @param array $moduleConfiguration
     * @return bool|array
     */
    protected function resolveRegExp(array $moduleConfiguration)
    {
        foreach ($moduleConfiguration['functions'] as $regexp => $value) {
            if (preg_match('#^'.$regexp.'$#', $this->pathString)) {
                return $regexp;
            }
        }
        return false;
    }
}
