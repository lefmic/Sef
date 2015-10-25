<?php

global $_SERVER;

use Sef\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Mock\Module\Configuration\ModulesConfigurationMock;
use Mock\Module\Configuration\FallbackConfigurationMock;
use Mock\Module\Configuration\ForumConfigurationMock;
use Mock\Module\Configuration\MainConfigurationMock;

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_SERVER['HTTP_HOST'] = 'testhost.local';
        $_SERVER["QUERY_STRING"] = 'foo=bar';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:38.0) Gecko/20100101 Firefox/38.0';
    }

    /**
     * @covers Sef\Router\Router::resolvePath
     */
    public function testResolvePathExplodesRequestUriSuccessfully()
    {
        $_SERVER['REQUEST_URI'] = '/forum/thread/1/comment';
        $router = new RouterProxy();
        $router->setRequest(Request::createFromGlobals());
        $router->resolvePath();
        $this->assertEquals('forum', $router->getModuleString());
        $this->assertEquals('thread/1/comment', $router->getPathString());
    }

    /**
     * @covers Sef\Router\Router::resolvePath
     */
    public function testResolveProxyOnlyModuleExists()
    {
        $_SERVER['REQUEST_URI'] = '/forum';
        $router = new RouterProxy();
        $router->setRequest(Request::createFromGlobals());
        $router->resolvePath();
        $this->assertEquals('forum', $router->getModuleString());
        $this->assertEquals('', $router->getPathString());
    }

    /**
     * @covers Sef\Router\Router::resolvePath
     */
    public function testResolvePathPathNotExisting()
    {
        $router = new RouterProxy();
        $router->setRequest(Request::createFromGlobals());
        $router->resolvePath();
        $this->assertEquals('', $router->getModuleString());
        $this->assertEquals('', $router->getPathString());
    }

    /**
     * @covers Sef\Router\Router::resolveModule
     */
    public function testResolveModuleModuleExists()
    {
        $router = new Router();
        $modulesConf = new ModulesConfigurationMock();
        $forumConf = new ForumConfigurationMock();
        $router->setModuleString('Forum');
        $router->setModules($modulesConf->getConfiguration());
        $router->resolveModule();
        $this->assertEquals($forumConf->getConfiguration(), $router->getModuleConfiguration());
    }

    /**
     * @covers Sef\Router\Router::resolveModule
     */
    public function testResolveModuleExistenceReturnsFalse()
    {
        $router = new Router();
        $modulesConf = new ModulesConfigurationMock();
        $fallbackModule = new FallbackConfigurationMock();
        $router->setModuleString('foo');
        $router->setModules($modulesConf->getConfiguration());
        $router->resolveModule();
        $this->assertEquals($fallbackModule->getConfiguration(), $router->getModuleConfiguration());
    }

    /**
     * @covers Sef\Router\Router::process
     */
    public function testProcessSuccessfullySetUpRegexp()
    {
        $forumConf = new ForumConfigurationMock();
        $router = new Router();
        $router->setModuleString('forum');
        $router->setModuleConfiguration($forumConf->getConfiguration());
        $router->process();
        $this->assertEquals($forumConf->getConfiguration(), $router->getModuleConfiguration());
    }

    /**
     * @covers Sef\Router\Router::process
     */
    public function testProcessFallbackConfiguration()
    {
        $fallbackConf = new FallbackConfigurationMock();
        $mainConf = new MainConfigurationMock();
        $router = new Router();
        $router->setModuleConfiguration($mainConf->getConfiguration());
        $router->setPathString('no/match');
        $router->process();
        $this->assertEquals($fallbackConf->getConfiguration(), $router->getModuleConfiguration());
    }

    /**
     * @covers Sef\Router\Router::resolveRegExp
     */
    public function testResolveRegexpSuccessfullyFindsMatchingPath()
    {
        $configurationMock = new MainConfigurationMock();
        $confArr = $configurationMock->getConfiguration();
        $router = new RouterProxy();
        $router->setPathString('regexp/for/the/path');
        $this->assertEquals('regexp\/for\/the\/path\/?', $router->resolveRegExp($confArr));
    }

    /**
     * @covers Sef\Router\Router::resolveRegExp
     */
    public function testResolveRegexpDoesNotFindMatchingPath()
    {
        $configurationMock = new MainConfigurationMock();
        $confArr = $configurationMock->getConfiguration();
        $router = new RouterProxy();
        $router->setPathString('not/matching/path');
        $this->assertEquals(false, $router->resolveRegExp($confArr));
    }
}

class RouterProxy extends Router
{
    public function resolvePath()
    {
        parent::resolvePath();
    }

    public function resolveRegExp(array $moduleConfiguration)
    {
        return parent::resolveRegExp($moduleConfiguration);
    }
}