Template Engine Component
=========================
THIS COMPONENT IS UNDER CONSTRUCTION!!

`TemplateEngine` component provides a layer for rendering view templates. The definition of the view template depends
on the selected driver. By default the template engine comes with a driver for `Smarty`, but you can easily add
support for `Twig` or some other template engines.

The provided functionality of every driver is defined by the TemplateEngineInterface which defines the following methods:
- **fetch** - fetch the template from the given location, parse it and return the output
- **render** - fetch the template from the given location, parse it and output the result to the browser
- **assign** - assign a variable and its value into the template engine
- **setTemplatePath** - root path where the templates are stored
- **registerPlugin** - register a plugin for the template engine

To create a new driver just create a new class, implement the `\Webiny\Bridge\TemplateEngine\TemplateEngineInterface`
and adapt your config.

The default configuration looks like this:
```yaml
bridges:
    template_engine:
        smarty: '\Webiny\Bridge\TemplateEngine\Smarty\Smarty'
components:
    template_engine:
        engines:
            smarty:
                force_compile: false
                cache_dir: '/var/tmp/smarty/cache'
                compile_dir: '/var/tmp/smarty/compile'
                auto_escape_output: false
```

## Usage

The preferred usage is over the `TemplateEngineTrait`.
Here is an example:

```php
class MyClass
{
	use \Webiny\Component\TemplateEngine\TemplateEngineTrait;

	function __construct() {
	    // assing name and id to the template and render it
		$this->templateEngine('smarty')->render('template.tpl', ['name'=>'John', 'id'=>15]);
	}
}
```

## Plugins & extensions

The template engine is designed so that it can be expanded with different plugins and modifiers, depending on the assigned
driver.

Best practice for expanding the template engine is first to create an extension and then register it as a service
tagged with the `$driverName.extension`, for example `smarty.extension`.

An `extension` is a package of one or multiple plugins. Plugin type depends on the template engine, for example smarty
supports these plugin types:
- **functions** - http://www.smarty.net/docs/en/plugins.functions.tpl
- **modifiers** - http://www.smarty.net/docs/en/plugins.modifiers.tpl
- **blocks** - http://www.smarty.net/docs/en/plugins.block.functions.tpl
- **compiler functions** - http://www.smarty.net/docs/en/plugins.compiler.functions.tpl
- **pre filters** - http://www.smarty.net/docs/en/plugins.prefilters.postfilters.tpl
- **post filters** - http://www.smarty.net/docs/en/plugins.prefilters.postfilters.tpl
- **output filters** - http://www.smarty.net/docs/en/plugins.outputfilters.tpl
- **resources** - http://www.smarty.net/docs/en/plugins.resources.tpl
- **inserts** - http://www.smarty.net/docs/en/plugins.inserts.tpl

To create a smarty extension, create a class that extends `\Webiny\Components\TemplateEngine\Drivers\Smarty\SmartyExtension`
and then overwrite the methods, based on the plugin type your wish to create.

For example, let's say we want to register a modifier called 'customUpper'. First we create our extension class like this:

```php
namespace MyApp\Demo;

class MySmartyExtension extends \Webiny\Component\TemplateEngine\Drivers\Smarty\SmartyExtension
{
	/**
	 * @overwrite
	 * @return array
	 */
	function getModifiers(){
		return [
			new SmartySimplePlugin('custom_upper', 'modifier', [$this, 'customUpper'])
		];
	}

	/**
	 * Callback for my custom_upper modifier.
	 *
	 * @param $params
	 *
	 * @return string
	 */
	function customUpper($params){
		return strtoupper($params);
	}

	/**
	 * Returns the name of the plugin.
	 *
	 * @return string
	 */
	function getName() {
		return 'my_extension';
	}
}
```

Once we have our extension, we must register it using the service manager:

```yaml
services:
    template_engine:
        custom_extension:
            class: \MyApp\Demo\MySmartyExtension
            tags: [smarty.extension]
```

And that's it, we can now use the modifier in our templates:

```php
{'this is my name'|custom_upper}
// outputs: THIS IS MY NAME
```

http://symfony.com/doc/current/book/templating.html