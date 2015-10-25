
# SEF - Simple Extensible Framework #

SEF is a simple and extensible micro-framework which uses [symfony/HttpFoundation](https://github.com/symfony/HttpFoundation)
and [PHP-DI](https://github.com/PHP-DI/PHP-DI) to deliver you a starting point for your web application.

It is designed for developers that like to decide which packages they want to use in their application on their own.

This framework defines only a few guidelines for the developer:

*   The application is divided in modules
*   The first slash-separated string of the url-path defines the module
*   The rest of the path is needed to define which method of the module controller to call
*   These definitions must be done in configuration files

These are the steps for you to do if you want to use this framework:

1.  Get the files using composer.

```
   "require": {
        "sef/sef": "~1.0"
    }
```

2.  Write configurations for your modules, their functions and the dependencies
3.  Implement your business logic

## Configure your application ##

You will basically need two configuration files.

1.  Configuration with all modules listed in an array where the module name is the key and the namespace of its specific configuration is the value.
2.  The specific configuration file for every module.

### Example of application configuration (the 1st one): ###

    // The ConfigurationInterface ensures you have implemented the getConfiguration() method 
    class MyAppConfiguration implements Sef\Configuration\ConfigurationInterface
    {
        public function getConfiguration()
        {
            // this method MUST return an array
            return array(
                // The key 'modules' is required for the framework to know which modules you have implemented
                // Define all active modules inside of this array
                // This makes it easy for you to deactivate a module by just deleting one line in this array
                // Make sure the value is referencing to a configuration Class
                // The look of the Module Configuration Class is described in "Example of a module-configuration (the 2nd one)"
                'modules' => array(
                    // ModuleName1 == your freely defined module name - this will be the first part of the path you expect
                    // namespace\to\moduleConfiguration1 == Configuration file, depending to this module
                    'ModuleName1' => 'namespace\to\moduleConfiguration1',
                    'ModuleName2' => 'namespace\to\moduleConfiguration2',
                    'ModuleName3' => 'namespace\to\moduleConfiguration3',
                ),
                // Here you have to define a fallback module
                'fallback' => array(
                    // namespace\to\FallbackModuleConfiguration == Configuration file, depending to this module (can be every module you want)
                    'module' => 'namespace\to\FallBackModuleConfiguration',
                    // path regexp in the referenced Module Configuration to use (explanation below)
                    'regexp' => ''
                ),
            );
        }
    }

#### Fallback Explanation: ####
*   The Fallback module is needed if a user will try to open a path to a module that is not existing or is deactivated.
*   You can define every module you want as a general fallback.
*   Despite it is the fallback itself, it doesn't need an own fallback function except it is not used as a regular module, too.

##### Why to type in a regexp? #####
As you can see in the module-configuration example below, the methods to call within a Controller are referenced by regular expressions,
which are matching the path in the URL.
For writing less code and keep this framework slim and simple, you will write your fallback module just like all other modules and
use the same structure. Therefore you will reference the method in the fallback to call with the appropriate regexp.

##### Here is an example of a fallback #####
    
    // The ConfigurationInterface ensures you have implemented the getConfiguration() method
    class FallbackConfiguration implements Sef\Configuration\ConfigurationInterface
    {
        public function getConfiguration()
        {
            return array(
                // Define some dependencies if you need
                'dependencies' => array(
                    // The controller for your Fallback
                    'Controller' => DI\object('Test\Module\ErrorController')->lazy()
                ),
                // Looks same line a regular module configuration, except the fallback array
                'functions' => array(
                    '' => array(
                        'method' => 'runMain',
                        'dependencies' => array()
                    ),
                ),
            );
        }
    }

#### NOTE: ####

*   This configuration must contain a modules-array and a fallback-array inside
*   The modules-array defines all possible modules
*   The fallback will be executed if a module was not found

## Configure your modules ##

### Example of a module-configuration (the 2nd one): ###

    // The ConfigurationInterface ensures you have implemented the getConfiguration() method
    class MyModuleConfiguration implements Sef\Configuration\ConfigurationInterface
    {
        public function getConfiguration()
        {
            // An array MUST be returned here
            return array(
                // Define dependencies if you want to
                 'dependencies' => array(
                    // This is the controller to use for the methods listed here.
                    // You can also define a new controller for each method if you wish.
                    // If you defined one controller here and another for some special method, then this controller will not be executed
                    'Controller' => DI\object('namespace\to\your\controller')->lazy())
                 ),
                 // Define functions that will be called in case the appropriate regular expression matches a given path
                 'functions' => array(
                     // Define the expression that will determine the called path
                     'regexp\/for\/the\/path\/?' => array(
                        // Define the method to call inside the controller
                        'method' => 'methodToCall',
                        // Specific dependencies for the method above
                        'dependencies' => array(
                            // This is the controller of the method defined above
                            // This controller can either be defined in every method so you can define own dependencies every time
                            // or you define the controller in the first level dependencies - its up to you
                            'Controller' => DI\object('namespace\to\your\controller')->lazy())
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

    -   You can use all types of injections, explained on the [PHP-DI homepage](http://php-di.org/doc/php-definitions.html)
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
### .htaccess ###
- I suggest you use a .htaccess that looks at least like this
```
    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.+)$ index.php?uri-$1 [QSA,L]
```