<?php

namespace Sef\Router;

use Sef\Configuration\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Router
 *
 * Determines the module and the matching regular expression for the path
 *
 * @package Sef\Router
 */
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
     * resolve the given url path
     */
    public function resolvePath()
    {
        $requestUri = trim($this->request->getPathInfo(), '/');
        $urlArr = explode('/', $requestUri, 2);
        $this->moduleString = $urlArr[0];
        $this->pathString = isset($urlArr[1]) ? $urlArr[1] : null;
    }

    /**
     * check whether the desired module exists in the configuration
     * and set the appropriate module configuration to use
     */
    public function resolveModule()
    {
        if (!array_key_exists($this->moduleString, $this->modules['modules'])) {
            $moduleConf = $this->modules['fallback']['module'];
            $this->moduleIsFallback = true;
        } else {
            $moduleConf = $this->modules['modules'][$this->moduleString];
        }
        /**
         * @var ConfigurationInterface $moduleConfiguration
         */
        $moduleConfiguration = new $moduleConf();
        $this->moduleConfiguration = $moduleConfiguration->getConfiguration();
    }

    /**
     * determines whether to use the given module or a fallback
     * depending on the match of the regular expression
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
     * find a matching regular expression for the path in the given configuration or return false
     *
     * @param array $moduleConfiguration
     * @return false|array
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
