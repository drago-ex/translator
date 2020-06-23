<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago Extension</h3>
<p align="center">Simple packages built on Nette Framework</p>

## Drago Translator
Simple translator.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/translator/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![Build Status](https://travis-ci.org/drago-ex/translator.svg?branch=master)](https://travis-ci.org/drago-ex/translator)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/translator/badge)](https://www.codefactor.io/repository/github/drago-ex/translator)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/translator/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/translator?branch=master)

## Technology
- PHP 7.1 or higher
- composer

## Installation
```
composer require drago-ex/translator
```

## Extension registration
```php
extensions:
	- Drago\Localization\DI\TranslatorExtension(%appDir%/locale)
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

## We will create the translation in ini files
```php
hello.world = Hello, world'!'
```

## Translation in the template
```latte
{_'hello.world'}
```

## Translation in forms
```php
$form->setTranslator($this->getTranslator());
$form->addText('hello', 'hello.world');
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
