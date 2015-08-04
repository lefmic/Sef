
Configure your application.

Example of application configuration:

array(
    'moduleName1' => 'namespace/to/moduleName1',
    'moduleName2' => 'namespace/to/moduleName2',
    'moduleName3' => 'namespace/to/moduleName3',
);

Example of minimum module-configuration-array:

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