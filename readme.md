## Drago Translator

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c816f793fb404487ad7a565c4374ae74)](https://www.codacy.com/app/accgit/translator?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/translator&utm_campaign=badger)

Little translator.

## Parameter language

We put this parameter into Presenter so we know what translation to use:

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

## We'll get the translation as an array

```php
/**
 * @return Drago\Localization\Translator
 */
public function getTranslator()
{
	return new Drago\Localization\Translator(__DIR__ . '/localization.ini);
}
```

##  Translation in templates

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

##  Macro for translation in template

```latte
{_'hello.word'}
```

## Translation form

```php
$form->setTranslator($this->getTranslator());
$form->addText('hello', 'hello.word');
```

## Edit routes

```php
$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Switch language in template

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
