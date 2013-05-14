Config Component
=====================
Config component creates Config objects from config files.
Currently only INI files are supported.

To use Config component you will need a config file.

===== Example INI File =====

    [properties]
    a = "value"
    b.name = "name"
    b.value = "value"

Here is an example of creating a Config object:

    $config = \Webiny\Components\Config\Config::Ini('path/to/file.ini');

This will result in $config object containing the following properties:

    $config->properties->a = 'value';
    $config->properties->b->name = 'name';
    $config->properties->b->value = 'value';

Config is using internal caching system, so if you call this twice, you will get the cached value, including any config changes you made through the code.
If, however, you need an original config from file, you need to specify the second parameter, $flushCache. This will reload the file and overwrite the existing cache:

    $config = \Webiny\Components\Config\Config::Ini('path/to/file.ini', true);


You can save your config in any format using the following methods:

    $config->saveAsJson($pathToFile);
    $config->saveAsPhp($pathToFile);
    $config->saveAsIni($pathToFile);
    $config->saveAsYaml($pathToFile);

    // This will save your config object to the file used when loading config
    $config->save();

And you can also use custom driver:

    $driverInstance = new MyCustomDriver();
    $config->saveAs($driverInstance);

You can get your config as string in any format using the following methods:

    $string = $config->getAsJson();
    $string = $config->getAsPhp();
    $string = $config->getAsIni();
    $string = $config->getAsYaml();

And you can also use custom driver

    $driverInstance = new MyCustomDriver();
    $string = $config->getAs($driverInstance);



