## Drago Translator
Simple and lightweight translator for the Nette Framework.
Provides localization using NEON translation files with support
for global and optional module-specific translations.

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
Register the DI extension in your NEON configuration.
You must provide a base directory for translation files.
```neon
extensions:
	- Drago\Localization\DI\TranslatorExtension(translateDir: %appDir%/locale)
```

## Optional configuration
```neon
translator:
	moduleLocaleDir: %appDir%/modules/Blog/locale
```

## The translator supports multiple translation directories.
- The base directory is always loaded first
- The optional module directory is loaded second
- If translation keys collide, later directories override earlier ones

Translation files must be named by language code:
```
cs.neon
en.neon
```

## Translation File Format
```neon
"Hello, world!": "Hello, world!"
```

## Using Translator in Presenters
Add the TranslatorAdapter trait to your presenter:
```php
use Drago\Localization\TranslatorAdapter;
```
The trait provides:
- persistent language parameter ($lang)
- automatic translator initialization
- template integration

## Accessing the Current Language
You can access the currently set language using the following property:
```php
$this->lang;
```

## Getting Translator Instance
To get the initialized translator for the current language:
```php
$this->getTranslator()
```

## Using Translations in Templates
The translator is automatically registered in templates.
Example usage in Latte:
```latte
{_"Hello, world!"}
{$label|translate}
```

## Using Translator in Forms
To enable translations in forms, set the translator explicitly:
```php
$form->setTranslator($this->getTranslator());
```

## Routing for Language Switching
To support language prefixes, configure your routes accordingly:
```php
$router->addRoute('[<lang=en cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Switching Languages in Templates
You can switch languages by passing the lang parameter:
```latte
<a n:href="this, lang => cs">Czech</a>
<a n:href="this, lang => en">English</a>
```

## Notes
- Translator loads translations lazily on first use
- Translations are loaded once per request
- Missing keys return the original message

