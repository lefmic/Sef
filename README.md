
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

- This configuration must contain a modules array and a fallback array inside
- The modules array defines all possible modules
- The fallback will be executed if a module was not found (this is a perfect place for your error template)

## Configure your modules. ##

### Example of minimum module-configuration (the 2nd one): ###

    class MyModuleConfiguration implements Sef\Configuration\ConfigurationInterface
    {
        public function getConfiguration()
        {
            return array(
                 'controller' => 'namespace\to\your\controller',
                 'dependencies' => array(
                     'Baz' => function (ContainerInterface $c) {
                         return new Baz();
                     },
                 ),
                 'functions' => array(
                     'regexp\/for\/the\/path\/?' => array(
                         'method' => 'methodToCall',
                         'dependencies' => array (
                             'Foo' => function (ContainerInterface $c) {
                                 return new Foo();
                             },
                             'Bar' => function (ContainerInterface $c) {
                                 return BarFactory::getInstance();
                             }
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

- Define the namespace to your controller
- Define dependencies that are used controller-wide
- Add an array of functions with its specific dependencies
- Add another fallback which will be called in case the given path does not match any given regular expression 

Note:
Make sure you define regular expressions as keys for the given functions.
Here is URL-pattern the framework expects: www.example.com/moduleName/this/will/be/checked/in/the/regular/expressions