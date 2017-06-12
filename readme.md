## Drago Translator

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/014959b4bea44e7eae6c0d9beb5102e9)](https://www.codacy.com/app/zdenek.papucik/translator?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=drago-ex/translator&amp;utm_campaign=Badge_Grade)

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
