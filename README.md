
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
                'Main' => 'namespace\to\MainConfiguration',
                'ModuleName2' => 'namespace\to\moduleName2',
                'ModuleName3' => 'namespace\to\moduleName3',
            );
        }
    }

#### NOTE: ####

- This configuration expects at least one module: "Main", which is the fallback module if everything else goes wrong.

## Configure your modules. ##

### Example of minimum module-configuration (the 2nd one): ###

    array(
         'controller' => 'namespace/to/your/controller',
         'dependencies' => array(
             'Baz' => function (ContainerInterface $c) {
                 return new Baz();
             },
         ),
         '/regexp.for.the.path/' => array(
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
    );