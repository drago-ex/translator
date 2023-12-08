## Drago Translator
Simple translator.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/translator/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![Tests](https://github.com/drago-ex/translator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/translator/badge)](https://www.codefactor.io/repository/github/drago-ex/translator)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/translator/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/translator?branch=master)

## Technology
- PHP 8.1 or higher
- composer

## Installation
```
composer require drago-ex/translator
```

## Extension registration
```neon
extensions:
	- Drago\Localization\DI\TranslatorExtension(translateDir: %appDir%/locale)
```

## Use in the presenter
```php
use Drago\Localization\TranslatorAdapter
```

## Currently set language
```php
$this->lang;
```

## A method that returns a translator
```php
$this->getTranslator();
```

## We will create the translation in neon files
```neon
"Hello, world!": "Hello, world!"
```

## Translation in the template
```latte
{_"Hello, world!'"}

{* or use filter *}
{$var|translate}
```

## Translation in forms
```php
$form->setTranslator($this->getTranslator());
```

## Route settings for translation
```php
$router->addRoute('[<lang=en cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Switching languages
```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
