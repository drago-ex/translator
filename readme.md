## Drago Translator

Little translator.

## Instruction

Instructions on how to easily translate application.

## Presenter

The presenter must create this public variable. The presenter must create
this public variable that gives us the current language parameter.

```php
/**
 * @var string
 * @persistent
 */
public $lang;
```

## Language file

Create in project localization.ini file and add your translation.

```ini
hello.word = Hello Word
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

To support translations in the template, use this:

```php
protected function beforeRender()
{
	parent::beforeRender();

	// Translation template.
	$this->template->setTranslator($this->getTranslator());
}
```

The template displays the translation follows:

```latte
{_'hello.word'}
```

## Translator route

Passing parameters for Routers. Insert to configuration file (.neon) this:

```yaml
services:

	# Settings router for multiple-language website.
	- Drago\Localization\Localize('cs', 'cs|en|de')

```

In this way we use route:

```php
class RouterFactory
{
	use Nette\SmartObject;

	/**
	 * @var Drago\Localization\Localize
	 */
	private $localize;

	public function __construct(Drago\Localization\Localize $localize)
	{
		$this->localize = $localize;
	}

	/**
	 * Route language.
	 * @return string
	 */
	private function locale()
	{
		return $this->localize->locale();
	}

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router[] = new Route($this->locale() . '<presenter>/<action>[/<id>]', 'Presenter:action');
		...
	}
}
```

Languages switch, add this to template:

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
