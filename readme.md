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

**TIP**
You can translate the entire application with one language or translate the application
as a parameter of the URL.

```php
$path = __DIR__ . '/' . $this->lang . '.ini';
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

Languages switch, add this to template:
```latte
<a n:href="this, 'lang' => 'en'">English</a>
```

Use translate with forms:
```php
// Forms
$form = New Form;
$form->setTranslator($this->getTranslator());
```

Passing parameters for Routers. Insert to configuration file this:
```yaml
parameters:

	# default translation
	locale: 'en'

	# list of available translations
	locales: 'en|de|cs'

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
