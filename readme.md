# Drago Translator

Lightweight translator for Nette Framework using NEON files, supporting global and module-specific translations.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/translator/blob/master/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![Tests](https://github.com/drago-ex/translator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml)

## Requirements
- PHP >= 8.3
- Nette Framework
- Composer

## Installation
```
composer require drago-ex/translator
```

## Extension Registration
Register the DI extension in your NEON configuration.
```neon
extensions:
	translator: Drago\Localization\DI\TranslatorExtension(%appDir%, %tempDir%)
```

## Optional configuration
```neon
translator:
	autoFinder: false
	translateDirs:
		- %appDir%/First/Translate
		- %appDir%/Second/Translate
	exclude:
		- %appDir%/Temp
		- %appDir%/Legacy
```

## Translator Behavior
- All directories listed in translateDirs are loaded in order.
- Later directories override translations from earlier ones.
- If autoFinder is enabled, the entire application directory is scanned for NEON files.
- Directories listed in exclude are skipped during automatic scanning.

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

## Language Switch Widget
The package provides a reusable Latte widget for language switching.
When project file copying is handled by `drago-ex/project-tools`, the widget is copied to:
```text
app/Core/Widget/@lang-switch.latte
```

Import the widget in your layout:
```latte
{import 'path/to/@lang-switch.latte'}
```

Render language links:
```latte
{include lang-switch, lang: 'cs', name: 'Czech'}
<span class="small ps-1 pe-1 text-secondary">|</span>
{include lang-switch, lang: 'en', name: 'English'}
```

The current language link automatically receives the `current` class.

Available options:
- `lang` - target language code.
- `name` - visible translated label.
- `class` - optional class added to the link.
- `tag` - optional wrapper tag: `li`, `div`, or `span`.
- `tagClass` - optional class added to the wrapper tag.

Use `class` when the link needs a custom class:
```latte
{include lang-switch, lang: 'cs', name: 'Czech', class: 'nav-link'}
```

Use `tag` when the link must be wrapped, for example in a dropdown menu:
```latte
{include lang-switch, lang: 'cs', name: 'Czech', tag: 'li'}
{include lang-switch, lang: 'en', name: 'English', tag: 'li'}
```

Use `tagClass` when the wrapper needs styling:
```latte
{include lang-switch, lang: 'cs', name: 'Czech', tag: 'li', tagClass: 'dropdown-item-wrapper'}
```

## Notes
- Translator loads translations lazily on first use
- Translations are loaded once per request
- Missing keys return the original message
