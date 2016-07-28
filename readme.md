## Translator adapter

Little translator for applications.

## Instruction

Instructions on how to easily translate application.

```php
/**
 * @var string
 * @persistent
 */
public $lang;
```

Add this method to Presenter:

```php

/**
 * @return Translator
 */
public function getTranslator()
{
	return new Drago\Localization\Translator(__DIR__ . '/localization.ini);
}
```

Create in project localization.ini file and add your translation.

```ini
hello.word = Hello Word
```

To support translations in the template, use this:

```php
protected function beforeRender()
{
	parent::beforeRender();
	$this->template->setTranslator($this->getTranslator());
}
```

In Templates using the underscore:

```latte
{_'hello.word'}
```

## Translator route

Passing parameters for Routers. Insert to configuration file this:

```yaml
parameters:

	# default translation
	locale: 'cs'

	# list of available translations
	locales: 'cs|en'

```

The parameters passed to router:

```yaml
services:
	router: RouterFactory::createRouter(%locale%, %locales%)
```

In this way we use route:

```php
use Drago\Localization\Route as Lang;

class RouterFactory
{
	public static function createRouter($locale, $locales)
	{
		$lang = Lang::locale($locale, $locales);
		$router[] = new Route($lang . '<presenter>/<action>[/<id>]', 'Presenter:action');
		...
	}
}
```

Languages switch, add this to template:

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
