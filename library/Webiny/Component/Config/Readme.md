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

============================

Here is an example of creating a Config object:

$config = \Webiny\Components\Config\Config::Ini('path/to/file.ini');

This will result in $config object containing the following properties:

$config->properties->a = 'value';
$config->properties->b->name = 'name';
$config->properties->b->value = 'value';



