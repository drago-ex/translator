## Drago Translator
Simple and lightweight translator for Nette Framework, providing localization support using NEON translation files.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/translator/master/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![Tests](https://github.com/drago-ex/translator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/translator/badge)](https://www.codefactor.io/repository/github/drago-ex/translator)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/translator/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/translator?branch=master)

## Requirements
- PHP >= 8.3
- Nette Framework
- Composer

## Installation
```
composer require drago-ex/translator
```

## Extension registration
Register the `Drago\Localization\DI\TranslatorExtension` in your Nette project by adding the following
configuration to your `neon` file:
```neon
extensions:
	- Drago\Localization\DI\TranslatorExtension(translateDir: %appDir%/locale)
```

## Use in the presenter
To use the translator in your presenter, add the `TranslatorAdapter` trait:
```php
use Drago\Localization\TranslatorAdapter
```

## Accessing the Current Language
You can access the currently set language using the following property:
```php
$this->lang;
```

## Get Translator Instance
To get the translator instance, use the `getTranslator` method:
```php
$this->getTranslator();
```

## Translation File Format
Translation files should be written in the NEON format. For example:
```neon
"Hello, world!": "Hello, world!"
```

## Using Translations in Templates
You can translate strings directly in your Latte templates using the following syntax:
```latte
{_"Hello, world!"}

{* Using a filter for translation *}
{$var|translate}
```

## Translating Forms
To use translations in forms, simply set the translator for the form:
```php
$form->setTranslator($this->getTranslator());
```

## Route Configuration for Language Switching
Set up your routes to support language prefixes. For example, you can define routes with language codes:
```php
$router->addRoute('[<lang=en cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Switching Languages in Templates
To switch between languages in your templates, you can use n:href to pass the selected language:
```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
