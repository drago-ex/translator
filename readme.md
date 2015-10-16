## Translator adapter
Little translator for applications.

## Instruction
Instructions on how to easily translate application.
```php
/**
 * Read more about persistent annotation https://doc.nette.org/en/2.3/presenters#toc-persistent-parameters
 * @var string
 * @persistent
 */
public $lang;
```
Add this method to Presenter:
```php
use Drago\Localization\Translator;

/**
 * @return Translator
 */
public function getTranslator()
{
	$path = __DIR__ . '/localization.ini';
	return new Translator(parse_ini_file($path));
}
```

Create in project localization.ini file and add your translation.
```ini
hello.word = Hello Word
...
```

**TIP:**
You can translate the entire application with one language or translate the application
as a parameter of the URL.

```php
$path = __DIR__ . '/' . $this->lang . '.ini';
```

For template use this
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

Used to translate the Presenter:
```php
// Forms
$form = New Form;
$form->setTranslator($this->getTranslator());

// Form errors
$form->addError('hello.word');

// Flash Messages
$this->flashMessage('hello.word));
```

Passing parameters for Routers. Insert to configuration file this:
```neon
parameters:

	# default translation
	locale: 'en'

	# list of available translations
	locales: 'en|de|cs'

```
The parameters passed to router:

```neon
services:
	router: RouterFactory::createRouter(%locale%, %locales%)
```

In this way we use route:
```php
use Drago\Localization\Route as Lang;
...

class RouterFactory
{
	public static function createRouter($locale, $locales)
	{
		$router[] = new Route(Lang::locale($locale, $locales) . '<presenter>/<action>[/<id>]', 'Presenter:action');
		...
	}
}
```
