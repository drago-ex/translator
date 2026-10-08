# Drago Translator

Lightweight translator for Nette Framework using NEON files, supporting
global, module-specific, and package translations.

[![License:
MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/translator/blob/master/license)
[![PHP
version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![Tests](https://github.com/drago-ex/translator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/tests.yml)
[![Coding
Style](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml)

## Requirements

-   PHP \>= 8.3
-   Nette Framework
-   Composer

## Installation

``` bash
composer require drago-ex/translator
```

## Extension Registration

Register the DI extension in your NEON configuration.

``` neon
extensions:
    translator: Drago\Localization\DI\TranslatorExtension(%appDir%, %tempDir%)
```

## Optional configuration

``` neon
translator:
    autoFinder: false
    translateDirs:
        - %appDir%/First/Translate
        - %appDir%/Second/Translate
    exclude:
        - %appDir%/Temp
        - %appDir%/Legacy
```

### Configuration options

-   `autoFinder` - automatically searches the application directory for
    translation files. Enabled by default.
-   `translateDirs` - additional translation directories. They can be
    configured by the application or registered by another DI extension.
-   `exclude` - directories excluded from automatic translation file
    discovery.

When `autoFinder` is enabled, `translateDirs` are loaded in addition to
the automatically discovered translation files.

## Translator Behavior

-   Automatically discovered translation files are searched recursively
    in the application directory when `autoFinder` is enabled.
-   Directories listed in `translateDirs` are loaded in addition to
    automatically discovered translations.
-   Translation directories are loaded in order.
-   Later translations override earlier translations when the same key
    is used.
-   Directories listed in `exclude` are skipped during automatic
    scanning.
-   Missing keys return the original message.

Translation files must be named by language code:

``` text
cs.neon
en.neon
```

## Package Translations

Packages can provide their own translation files and register their
translation directory through their DI extension.

A package can register its translation directory with the translator
extension:

``` php
use Drago\Localization\DI\TranslatorExtension;

public function loadConfiguration(): void
{
    $translator = $this->compiler->getExtension('translator');

    if ($translator instanceof TranslatorExtension) {
        $translator->addTranslateDir(__DIR__ . '/../lang');
    }

    // Package services...
}
```

The package can then keep its translations in its own `lang` directory:

``` text
src/
└── Drago/
    └── Package/
        ├── DI/
        │   └── PackageExtension.php
        └── lang/
            ├── cs.neon
            └── en.neon
```

This allows a package to provide translations without requiring the
application to register the package translation directory manually.

If the translator extension is not installed, the package can simply
skip translation registration.

## Translation File Format

``` neon
"Hello, world!": "Hello, world!"
"Hello, %s!": "Ahoj, %s!"
"You have %d items in your cart.": "V košíku máte %d položek."
```

## Parameters in Translations

Translations can contain `sprintf`-style placeholders. Pass their values
after the message key; the translator inserts them in order using PHP's
`vsprintf()`:

``` php
$translator->translate('Hello, %s!', 'Jane');
// Ahoj, Jane!

$translator->translate('You have %d items in your cart.', 3);
// V košíku máte 3 položek.
```

The same works with the translator registered in Latte:

``` latte
{_'Hello, %s!', $name}
{_'You have %d items in your cart.', $itemCount}
```

Use a matching placeholder for each argument, in the same order. Common
placeholders include `%s` for text, `%d` for an integer, and `%.2f` for
a decimal number with two digits after the decimal point. When no
translation exists, the original message is used and its placeholders
are formatted in the same way.

## Using Translator in Presenters

Add the TranslatorAdapter trait to your presenter:

``` php
use Drago\Localization\TranslatorAdapter;
```

The trait provides: - persistent language parameter (`$lang`) -
automatic translator initialization - template integration

## Accessing the Current Language

You can access the currently set language using the following property:

``` php
$this->lang;
```

## Getting Translator Instance

To get the initialized translator for the current language:

``` php
$this->getTranslator()
```

## Using Translations in Templates

The translator is automatically registered in templates.

Example usage in Latte:

``` latte
{_"Hello, world!"}
{$label|translate}
```

## Using Translator in Forms

To enable translations in forms, set the translator explicitly:

``` php
$form->setTranslator($this->getTranslator());
```

## Routing for Language Switching

To support language prefixes, configure your routes accordingly:

``` php
$router->addRoute('[<lang=en cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Switching Languages in Templates

You can switch languages by passing the lang parameter:

``` latte
<a n:href="this, lang => cs">Czech</a>
<a n:href="this, lang => en">English</a>
```

## Language Switch Widget

The package provides a reusable Latte widget for language switching.

When project file copying is handled by `drago-ex/project-tools`, the
widget is copied to:

``` text
app/Presentation/Accessory/Widget/@lang-switch.latte
```

Import the widget in your layout:

``` latte
{import 'path/to/@lang-switch.latte'}
```

Render language links:

``` latte
{include lang-switch, lang: 'cs', name: 'Czech'}
<span class="small ps-1 pe-1 text-secondary">|</span>
{include lang-switch, lang: 'en', name: 'English'}
```

The current language link automatically receives the `current` class.

Available options: - `lang` - target language code. - `name` - visible
translated label. - `class` - optional class added to the link. -
`tag` - optional wrapper tag: `li`, `div`, or `span`. - `tagClass` -
optional class added to the wrapper tag.

Use `class` when the link needs a custom class:

``` latte
{include lang-switch, lang: 'cs', name: 'Czech', class: 'nav-link'}
```

Use `tag` when the link must be wrapped, for example in a dropdown menu:

``` latte
{include lang-switch, lang: 'cs', name: 'Czech', tag: 'li'}
{include lang-switch, lang: 'en', name: 'English', tag: 'li'}
```

Use `tagClass` when the wrapper needs styling:

``` latte
{include lang-switch, lang: 'cs', name: 'Czech', tag: 'li', tagClass: 'item-wrapper'}
```

## Notes

-   Translator loads translations lazily on first use.
-   Translations are loaded once per request.
-   Missing keys return the original message.
