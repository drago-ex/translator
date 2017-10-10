## Drago Translator

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c816f793fb404487ad7a565c4374ae74)](https://www.codacy.com/app/accgit/translator?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/translator&utm_campaign=badger)

Malý jednoduchý překladač.

## Požadavky

- PHP 7.0.8 nebo vyšší
- composer

## Instalace

```
composer require drago-ex/translator
```

## Jak začít

V balíčku se nachází traita Locales, kterou přidáme do base presenteru, abychom měli přístup k parametru
$this->lang a metodě, kterou zavoláme v presenteru a předáme ji soubor s překladem.

```php
use Drago\Localization\Locales
```

## Jak vytvořit soubor s překladem

Kdekoliv v projektu vytvoříme soubory s názvem cs.ini, en.ini ve kterých si nadefinujeme potřebný překlad.

```ini
hello.world = Ahoj světe
```

## Jak zpracovat soubor s překladem

V presenteru vytvoříme metodu, které předáme soubor s překladem dle aktuálního parametru jazyka.

```php
/**
 * Překlad aplikace dle aktuálního parametru jazyka.
 * @return array
 */
public function translate()
{
	return $this->getTranslator(__DIR__ . '/locales/' . $this->lang . '.ini');
}
```

## Jak poslat překlad do šablony

```php
protected function beforeRender()
{
	parent::beforeRender();

	// Aktuální parametr jazyka.
	$this->template->lang = $this->lang;

	// Překlad pro šablony.
	$this->template->setTranslator($this->translate());
}
```

## Jak vypsat překlad v šabloně

```latte
{_'hello.world'}
```

## Jak nastavit překlad pro formuláře

```php
$form->setTranslator($this->translate());
$form->addText('hello', 'hello.world');
```

## Jak upravit routu pro jednotlivé překlady

```php
$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Jak přepínat mezi jednotlivými jazyky

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
