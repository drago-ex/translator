## Drago Translator

Little translator.

## Parameter language

The presenter must create this public variable that gives us the current language parameter:

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

## The method, which returns us to array of translation

This method will create the presenter:

```php
/**
 * @return Drago\Localization\Translator
 */
public function getTranslator()
{
	return new Drago\Localization\Translator(__DIR__ . '/localization.ini);
}
```

## Translation templates

To support translations in the template, use this:

```php
protected function beforeRender()
{
	parent::beforeRender();

	// Add to template language.
	$this->template->lang = $this->lang;

	// Translation template.
	$this->template->setTranslator($this->getTranslator());
}
```

The template displays the translation follows:

```latte
{_'hello.word'}
```

## Translation form

To translate the forms using this method:

```php
$form->setTranslator($this->getTranslator());
$form->addText('hello', 'hello.word');
```

## Edit route

Do we need to add route settings for languages:

```php
$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Languages switch, add this to template

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
## Tip for route

If you have more route and know that it will add another translation, 
will you throw this simple tip for editing route for translation.

In the configuration file, specify this parameter.

```yaml
parameters:

	# route for translation
	routers: '[<lang cs|en>/]'

services:

	# transmission parameters for route
	router: App\RouterFactory::createRouter(%routers%)
```

And then modify the class itself:

```php
class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter($locales)
	{
		$router = new RouteList;
		$router[] = new Route($locales . '<presenter>/<action>', 'Homepage:default');
		return $router;
	}

}
```
