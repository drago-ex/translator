## Drago Translator

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c816f793fb404487ad7a565c4374ae74)](https://www.codacy.com/app/accgit/translator?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/translator&utm_campaign=badger)

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
use Drago\Localization\Locales
```

## Creating translation files

Anywhere in a project we create a file called en.ini in which we will define the necessary translation.

```ini
hello.world = Hello world
```

## Processing files with translation

In the Presenter, we create the method below and add the path to the translation files.

```php
/**
 * Translation of the application according to the current language parameter.
 */
protected function translate(): array
{
	return $this->createTranslator(__DIR__ . '/' . $this->lang . '.ini');
}
```

## Set translation for templates

```php
protected function beforeRender(): void
{
	parent::beforeRender();

	// The current language parameter.
	$this->template->lang = $this->lang;

	// Translation for Templates.
	$this->template->setTranslator($this->translate());
}
```

## Macro for translation in template

```latte
{_'hello.world'}
```

## Translation for forms

```php
$form->setTranslator($this->translate());
$form->addText('hello', 'hello.world');
```

## Route for translations

```php
$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Switching languages

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
