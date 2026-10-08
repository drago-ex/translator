# Drago Translator

Lightweight translator for Nette Framework using NEON files. Translation directories can be declared automatically in Composer packages and in the root application, with an optional manual NEON configuration for special cases.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/translator/blob/master/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![Tests](https://github.com/drago-ex/translator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml)

## Requirements
- PHP >= 8.3
- Nette Framework
- Composer 2.1+

## Installation
```bash
composer require drago-ex/translator
```

## Extension Registration

Register the DI extension in your NEON configuration:

```neon
extensions:
	translator: Drago\Localization\DI\TranslatorExtension(%appDir%, %tempDir%)
```

No translation directories are required in NEON for the normal case. The translator discovers them from Composer metadata.

## Composer Translation Discovery

Each Composer package can declare one directory containing its translation files in `composer.json`:

```json
"extra": {
	"drago-translator": {
		"translation": "src/Drago/Commerce/Translate"
	}
}
```

The path is always relative to the root of that Composer package.

For example, a package installed as:

```text
vendor/drago-ex/commerce/
```

with:

```json
"translation": "src/Drago/Commerce/Translate"
```

provides translations from:

```text
vendor/drago-ex/commerce/src/Drago/Commerce/Translate/
```

The package does not need to depend on `drago-ex/translator`. The metadata is simply available for applications that use this translator. Other translation systems can ignore it.

### Root application

The root `composer.json` can declare the application's translation directory in exactly the same way:

```json
"extra": {
	"drago-translator": {
		"translation": "app/Presentation/Sign/Translate"
	}
}
```

The path is then resolved relative to the project root.

This makes the setup automatic after installing a package. The application does not need to add a new NEON entry for every vendor package.

## Translation precedence

Translation sources are loaded in this order:

1. Composer vendor packages.
2. The root application's Composer translation directory.
3. Translation directories configured manually in NEON.

Later sources override translations loaded earlier. This means an application can override a translation supplied by a vendor package.

For example, if both a package and the application define:

```neon
cart.add: "Add to cart"
```

the application's value wins.

## Manual translation directories

Composer discovery is the recommended approach, but a manual fallback is available for special cases:

```neon
translator:
	translateDirs:
		- %appDir%/Special/Translate
```

Multiple directories are supported when needed:

```neon
translator:
	translateDirs:
		- %appDir%/First/Translate
		- %appDir%/Second/Translate
```

They are loaded after Composer-discovered translations, in the configured order. Later directories override earlier ones.

There is no automatic recursive scan of the whole application directory. Every translation source must therefore be explicitly declared either in Composer metadata or in `translateDirs`.

## Translation Files

Translation files must use the language code as their filename, for example:

```text
cs.neon
en.neon
de.neon
```

The translator also accepts files whose name starts with the language code, for example `cs-CZ.neon` when requested as `cs`.

## Translation File Format

```neon
"Hello, world!": "Hello, world!"
"Hello, %s!": "Ahoj, %s!"
"You have %d items in your cart.": "V košíku máte %d položek."
```

## Parameters in Translations

Translations can contain `sprintf`-style placeholders. Pass their values after the message key; the translator inserts them in order using PHP's `vsprintf()`:

```php
$translator->translate('Hello, %s!', 'Jane');
// Ahoj, Jane!

$translator->translate('You have %d items in your cart.', 3);
// V košíku máte 3 položek.
```

The same works with the translator registered in Latte:

```latte
{_'Hello, %s!', $name}
{_'You have %d items in your cart.', $itemCount}
```

Use a matching placeholder for each argument, in the same order. Common placeholders include `%s` for text, `%d` for an integer, and `%.2f` for a decimal number with two digits after the decimal point.

When no translation exists, the original message is used and its placeholders are formatted in the same way.

## Using Translator in Presenters

Add the `TranslatorAdapter` trait to your presenter:

```php
use Drago\Localization\TranslatorAdapter;
```

The trait provides:
- persistent language parameter (`$lang`)
- automatic translator initialization
- template integration

## Accessing the Current Language

You can access the currently set language using:

```php
$this->lang;
```

## Getting Translator Instance

To get the initialized translator for the current language:

```php
$this->getTranslator();
```

## Using Translations in Templates

The translator is automatically registered in templates.

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

You can switch languages by passing the `lang` parameter:

```latte
<a n:href="this, lang => cs">Czech</a>
<a n:href="this, lang => en">English</a>
```

## Language Switch Widget

The package provides a reusable Latte widget for language switching.
When project file copying is handled by `drago-ex/project-tools`, the widget is copied to:

```text
app/Presentation/Accessory/Widget/@lang-switch.latte
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
{include lang-switch, lang: 'cs', name: 'Czech', tag: 'li', tagClass: 'item-wrapper'}
```

## Notes

- Composer package translation directories are discovered from `extra.drago-translator.translation`.
- Each Composer package declares one translation directory.
- The root project can declare its own translation directory using the same metadata.
- Manual `translateDirs` remain available for exceptional cases.
- Translations are loaded lazily on first use.
- Missing keys return the original message.
