## Drago Translator

Simple translator.

## Requirements

- PHP 7.1 or higher
- composer

## Installation

```
composer require drago-ex/translator
```

## How to begin

Put the trait into the presenter.

```php
use Drago\Localization\Locale
```

## Creating translation files

Anywhere in a project we create a file called en.ini in which we will define the necessary translation.

```ini
hello.world = Hello, world'!'
```

## Processing files with translation

In the Presenter, we create the method below and add the path to the translation files.

```php
protected function getTranslator(): Translator
{
	return $this->createTranslator(__DIR__ . '/' . $this->lang . '.ini');
}
```

## Set translation for templates

```php
// The current language.
$this->template->lang = $this->lang;

// Translation in templates.
$this->template->setTranslator($this->translate());
```

## Macro for translation in template

```latte
{_'hello.world'}
```

## Translation for forms

```php
$form->setTranslator($this->getTranslator());
$form->addText('hello', 'hello.world');
```

## Route for translation

```php
$router->addRoute('[<lang cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Switching languages

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
