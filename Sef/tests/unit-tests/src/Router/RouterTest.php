<?php

global $_SERVER;

use Sef\Router\Router;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends PHPUnit_Framework_TestCase
{
    private $modulesArray = array(
        'Main' => 'Mock\Module\Configuration\MainConfigurationMock',
        'Forum' => 'Mock\Module\Configuration\ForumConfigurationMock',
    );

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
    public function testResolveModuleReturnsTrue()
    {
        $router = new RouterProxy();
        $router->setModuleString('forum');
        $result = $router->resolveModule($this->modulesArray);
        $this->assertTrue($result);
    }

    /**
     * @covers Sef\Router\Router::resolveModule
     */
    public function testResolveModuleReturnsFalse()
    {
        $router = new RouterProxy();
        $router->setModuleString('foo');
        $result = $router->resolveModule($this->modulesArray);
        $this->assertFalse($result);
    }

    /**
     * @covers Sef\Router\Router::process
     * @expectedException Exception
     */
    public function testProcessThrowsExceptionNoModuleGiven()
    {
        $router = new RouterProxy();
        $router->setRequest(Request::createFromGlobals());
        $router->process($this->modulesArray);
    }

    /**
     * @covers Sef\Router\Router::process
     * @expectedException Exception
     */
    public function testProcessThrowsExceptionModuleNotFoundInConfiguration()
    {
        $_SERVER['REQUEST_URI'] = '/foo';

        $router = new RouterProxy();
        $router->setRequest(Request::createFromGlobals());
        $router->process($this->modulesArray);
    }

    /**
     * @covers Sef\Router\Router::process
     */
    public function testProcessSuccessfullySetUpModuleConfiguration()
    {
        $_SERVER['REQUEST_URI'] = '/forum';

        $router = new RouterProxy();
        $router->setRequest(Request::createFromGlobals());
        $router->process($this->modulesArray);

        $this->assertEquals(array(), $router->getModuleConfiguration());
    }

}

class RouterProxy extends Router
{
    public function resolvePath()
    {
        parent::resolvePath();
    }

    public function resolveModule(array $modulesArray)
    {
        return parent::resolveModule($modulesArray);
    }
}