<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago</h3>
<p align="center">Simple packages built on Nette Framework</p>

## Info

Simple translator.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/translator/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/translator/badge)](https://www.codefactor.io/repository/github/drago-ex/translator)

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
