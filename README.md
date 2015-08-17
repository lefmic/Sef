
# SEF - Simple Extensible Framework #

SEF is a simple and extensible micro-framework which uses [symfony/HttpFoundation](https://github.com/symfony/HttpFoundation)
and [PHP-DI](https://github.com/PHP-DI/PHP-DI) to deliver you a starting point for your web application.

It is designed for developers that like to decide which packages they want to use in their application on their own.

This framework defines only a few guidelines for the developer:

*   The application is divided in modules
*   The first slash-separated string of the url-path defines the module
*   The rest of the path is needed to define which method of the module-controller to call
*   These definitions must be done in configuration files

These are the steps for you to do if you want to use this framework:

1.  Get the files using composer.


    "require": {
        "sef/sef/: "1.x.x"
    }
    
    
2.  Write configurations for your modules, their functions and the dependencies
3.  Implement your business logic

## Configure your application ##

You will basically need two configuration files.

1.  Configuration with all modules listed in an array where the module name is the key and the namespace of its specific configuration is the value.
2.  The specific configuration file for every module.

### Example of application configuration (the 1st one): ###

    class MyAppConfiguration implements Sef\Configuration\ConfigurationInterface
    {
        public function getConfiguration()
        {
            return array(
                'modules' => array(
                    'ModuleName1' => 'namespace\to\moduleConfiguration1',
                    'ModuleName2' => 'namespace\to\moduleConfiguration2',
                    'ModuleName3' => 'namespace\to\moduleConfiguration3',
                ),
                'fallback' => array(
                    'module' => 'namespace\to\FallBackModuleConfiguration',
                    'regexp' => ''
                ),
            );
        }
    }

#### NOTE: ####

*   This configuration must contain a modules-array and a fallback-array inside
*   The modules-array defines all possible modules
*   The fallback will be executed if a module was not found (this is a perfect place for your error-handling, since it is the fallback itself it does not have to contain some further fallback functionality like explained for a common module below)

## Configure your modules ##

### Example of a module-configuration (the 2nd one): ###

    class MyModuleConfiguration implements Sef\Configuration\ConfigurationInterface
    {
        public function getConfiguration()
        {
            return array(
                 'dependencies' => array(
                    'Baz' => function () {
                        return new Baz();
                    },
                    'Foo' => DI\object('namespace\to\Foo')->lazy()
                    'Bar' => DI\object('namespace\to\Bar')->lazy()
                 ),
                 'functions' => array(
                     'regexp\/for\/the\/path\/?' => array(
                        'method' => 'methodToCall',
                        'dependencies' => array(
                            'Controller' => DI\object('namespace\to\your\controller')->lazy()
                                ->property('setFoo', DI\get('Foo'))
                                ->property('setBar', DI\get('Bar')),
                        )
                     ),
                     '' => array(
                        'method' => 'anotherMethod',
                        'dependencies' => array(
                            'Controller' => DI\object('namespace\to\your\controller')->lazy(),
                        )
                     )
                 ),
                 'fallback' => array(
                    'module' => 'namespace\to\fallback\module\configuration',
                    'regexp' => ''
                 ),
            );
        }
    }

This one looks a bit more complicated, but it is simple indeed:

-   Define dependencies that are used controller-wide

    -   You can use all types of definitions, explained on the [PHP-DI homepage](http://php-di.org/doc/php-definitions.html)
    -   I suggest to define the definitions as "lazy" here, so the objects will only be created if you are really using them
    -   You can leave the dependencies array empty if you do not need any
    
-   Add an array of functions with its specific dependencies

    -   Unlike the controller-wide-dependencies, the dependencies defined in every function MUST have at least one dependency, which is the controller itself
    
-   Add another fallback which will be called in case the given path does not match any given regular expression 

**Note:**

-   **Make sure you define regular expressions as keys for the given functions.**
-   **Here is an example of the URL-pattern the framework expects: www.example.com/moduleName/this/will/be/checked/in/the/regular/expressions**

## Start the application ##

    $app = new Sef\Application();
    $app->start(new MyAppConfiguration());
    
That's it!

## Additional information ##

-   This framework is using [symfony/HttpFoundation](https://github.com/symfony/HttpFoundation) to determine the path, nevertheless it is not injected in your application automatically.
-   You will have to inject the Request object where you need using the configuration files
-   If you need to configure your dependency injection container before setting up the injections, just create an instance of DI\ContainerBuilder, do whatever you need to do and pass the object to Sef\Application::start as the second parameter
