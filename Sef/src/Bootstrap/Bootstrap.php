<?php

namespace Sef\Bootstrap;

use DI\ContainerBuilder;
use Sef\Configuration\ConfigurationInterface;
use Sef\Controller\ControllerInterface;
use Sef\Router\Router;
use Symfony\Component\HttpFoundation\Request;

class Bootstrap
{
    /**
     * @var \DI\Container $moduleDiContainer
     */
    private $moduleDiContainer;

    /**
     * @var ControllerInterface $controller
     */
    private $controller;

    /**
     * @var $method
     */
    private $method;

    /**
     * set up all necessary variables for the module
     *
     * @param ConfigurationInterface $configuration
     */
    public function setUp(ConfigurationInterface $configuration)
    {
        $router = new Router();
        $router->setRequest(Request::createFromGlobals());
        $router->process($configuration->getConfiguration());

        $moduleConfiguration = $router->getModuleConfiguration();
        $this->moduleDiContainer = $this->initDI($moduleConfiguration['di']);
        $this->controller = $moduleConfiguration['controller'];
        // TODO: determine the method inside of the Router
        $this->method = $moduleConfiguration['method'];
    }

    /**
     * start the user application
     */
    public function run()
    {
        /**
         * @var ControllerInterface $controller
         */
        $controller = new $this->controller;
        $controller->setDic($this->moduleDiContainer);
        $method = $this->method;
        $controller->$method();
    }

    /**
     * initialize the dependency injection container
     *
     * @param array $configuration
     * @return \DI\Container
     */
    protected function initDI(array $configuration)
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($configuration);
        return $builder->build();
    }
}
