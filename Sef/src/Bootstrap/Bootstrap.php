<?php

namespace Sef\Bootstrap;

use DI\ContainerBuilder;
use Sef\Configuration\ConfigurationInterface;
use Sef\Controller\ControllerInterface;
use Sef\Router\Router;
use Sef\Validator\ConfigurationValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Bootstrap
 *
 * This class will start the processes for setting up the application
 *
 * @package Sef\Bootstrap
 */
class Bootstrap
{
    /**
     * @var \DI\Container $moduleDiContainer
     */
    private $moduleDiContainer;

    /**
     * @var $method
     */
    private $method;

    /**
     * @var ContainerBuilder
     */
    private $builder = null;

    /**
     * @param ContainerBuilder $builder
     */
    public function setBuilder(ContainerBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * run processes for setting up the application
     *
     * @param ConfigurationInterface $modulesConfiguration configuration that defines all modules
     */
    public function setUp(ConfigurationInterface $modulesConfiguration)
    {
        $configurationValidator = new ConfigurationValidator();
        $router = new Router();
        $router->setRequest(Request::createFromGlobals());

        $configurationValidator->validateModulesConfiguration($modulesConfiguration->getConfiguration());
        $router->resolvePath();
        $router->setModules($modulesConfiguration->getConfiguration());
        $router->resolveModule();
        $configurationValidator->validateModuleConfiguration(
            $router->getModuleConfiguration(),
            $router->getModuleIsFallback()
        );
        $router->process();
        $moduleConfiguration = $this->resolveControllerDependency($router->getModuleConfiguration(), $router->getMatchingRegexp());
        $configurationValidator->validateModuleConfiguration(
            $moduleConfiguration,
            $router->getModuleIsFallback(),
            $router->getMatchingRegexp()
        );
        $this->method = $moduleConfiguration['functions'][$router->getMatchingRegexp()]['method'];

        $diArr = $this->mergeDi($moduleConfiguration['dependencies'], $moduleConfiguration['functions'][$router->getMatchingRegexp()]['dependencies']);
        $this->moduleDiContainer = $this->initDI($diArr);
    }

    /**
     * Resolve the controller to use for the method
     *
     * @param array $configuration
     * @param $regexp
     * @return array
     */
    private function resolveControllerDependency(array $configuration, $regexp)
    {
        if (
            array_key_exists('Controller', $configuration['dependencies']) &&
            array_key_exists('Controller', $configuration['functions'][$regexp]['dependencies'])
        ) {
            unset($configuration['dependencies']['Controller']);
        }
        return $configuration;
    }

    /**
     * start the application
     *
     * Call the module controller and the appropriate method
     *
     * @throws \DI\NotFoundException
     */
    public function run()
    {
        /**
         * @var ControllerInterface $controller
         */
        $controller = $this->moduleDiContainer->get('Controller');
        if ($controller instanceof ControllerInterface) {
            $controller->setDic($this->moduleDiContainer);
        }
        $method = $this->method;
        $controller->$method();
    }

    /**
     * initialize the dependency injection container
     *
     * @param array $configuration the merged array of dependencies to inject
     * @return \DI\Container
     */
    protected function initDI(array $configuration)
    {
        $builder = (null !== $this->builder) ? $this->builder : new ContainerBuilder();
        $builder->addDefinitions($configuration);
        return $builder->build();
    }

    /**
     * merge the module specific di-configuration and the method specific di-configuration
     *
     * @param array $moduleDi
     * @param array $functionDi
     * @return array
     */
    private function mergeDi(array $moduleDi, array $functionDi)
    {
        return array_merge_recursive($moduleDi, $functionDi);
    }
}
