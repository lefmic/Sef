
# SEF - Sinmpe Extensible Framework #

You will basically need two configuration files.

1. Configuration with all modules listed in an array with module name as key and the namespace of its specific
configuration.
2. The specific configuration file for every module.

## Configure your application. ##

### Example of application configuration (the 1st one): ###

    class MyAppConfiguration implements Sef\Configuration\ConfigurationInterface
    {
        public function getConfiguration()
        {
            return array(
                'modules' => array(
                    'ModuleName1' => 'namespace\to\moduleName1',
                    'ModuleName2' => 'namespace\to\moduleName2',
                    'ModuleName3' => 'namespace\to\moduleName3',
                ),
                'fallback' => array(
                    'module' => 'namespace\to\FallBackModuleConfiguration',
                    'regexp' => ''
                ),
            );
        }
    }

#### NOTE: ####

*   This configuration must contain a modules array and a fallback array inside
*   The modules array defines all possible modules
*   The fallback will be executed if a module was not found (this is a perfect place for your error template,

    since it is the fallback itself it does not have to contain some further fallback functionality like explained
    
    for a common module below)

## Configure your modules. ##

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
                         'dependencies' => array (
                            'Controller' => DI\object('namespace\to\your\controller')->lazy()
                                ->property('setFoo', DI\get('Foo'))
                                ->property('setBar', DI\get('Bar')),
                         )
                     )
                 ),
                 'fallback' => array(
                    'module' => 'namespace\to\fallback\module\controller',
                    'regexp' => ''
                 ),
            );
        }
    }

This one looks a bit more complicated, but it is simple indeed

-   Define dependencies that are used controller-wide

    -   You can use all types of definitions, explained on the [PHP-DI homepage](http://php-di.org/doc/php-definitions.html)
    -   I suggest to define the definitions as "lazy" here, so the objects will only be created if you are really using them
    -   One of the features of the php-di container is, that you can mix the different definition types up, like shown in the
        example above
    -   You can leave the dependencies array empty if you do not need any
    
-   Add an array of functions with its specific dependencies

    -   Unlike the controller-wide-dependencies, the dependencies defined in every function MUST have at least one dependency,
        which is the controller itself
    
-   Add another fallback which will be called in case the given path does not match any given regular expression 

**Note:**

-   **Make sure you define regular expressions as keys for the given functions.**
-   **Here is a URL-pattern the framework expects: www.example.com/moduleName/this/will/be/checked/in/the/regular/expressions**



