Config Component
=====================
Config component creates Config objects from config files.
Currently supported formats: INI, JSON, PHP, YAML and custom drivers.

To use Config component you will need a config file.

Example INI:

    [properties]
    a = "value"
    b.name = "name"
    b.value = "value"

Here is an example of creating a Config object:
```php
    $config = \Webiny\Components\Config\Config::Ini('path/to/file.ini');
```

This will result in $config object containing the following properties:

```php
    $config->properties->a = 'value';
    $config->properties->b->name = 'name';
    $config->properties->b->value = 'value';
```

Config is using internal caching system, so if you call this twice, you will get the cached value, including any config changes you made through the code.
If, however, you need an original config from file, you need to specify the second parameter, $flushCache. This will reload the file and overwrite the existing cache:

```php
    $config = \Webiny\Components\Config\Config::Ini('path/to/file.ini', true);
```

If you don't want to use INI sections, or set custom nest delimiter, specify the following arguments:
```php
    $config = \Webiny\Components\Config\Config::Ini('path/to/file.ini', false, false, '_');
```

You can save your config in any format using the following methods:
```php
    $config->saveAsJson($pathToFile);
    $config->saveAsPhp($pathToFile);
    $config->saveAsIni($pathToFile, $useSections = true, $nestDelimiter = '.');
    $config->saveAsYaml($pathToFile, $indent = 2, $wordWrap = false);

    // This will save your config object to the file used when loading config
    $config->save();
```

And you can also use custom driver:
```php
    $driverInstance = new MyCustomDriver();
    $config->saveAs($driverInstance, $destination);
```

You can get your config as string in any format using the following methods:
```php
    $string = $config->getAsJson();
    $string = $config->getAsPhp();
    $string = $config->getAsIni($useSections = true, $nestDelimiter = '.');
    $string = $config->getAsYaml($indent = 2, $wordWrap = false);
```
And you can also use custom driver

```php
    $driverInstance = new MyCustomDriver();
    $string = $config->getAs($driverInstance);
```


