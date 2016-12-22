## Drago Translator

Little translator.

## Presenter

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

This will create methods in the presenter that we get a array of translation:

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

## Edit route

Do we need to add rue settings for languages:

```php
$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', 'Homepage:default');
```

## Languages switch, add this to template:

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
