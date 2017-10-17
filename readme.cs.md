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

Níže uvedenou traitu vložíme do base presenteru.

```php
use Drago\Localization\Locales
```

## Vytváření souborů s překladem

Kdekoliv v projektu vytvoříme soubor s názvem cs.ini ve kterém si nadefinujeme potřebný překlad.

```ini
hello.world = Ahoj světe
```

## Zpracování souborů s překladem

V presenteru vytvoříme níže uvedenou metodu, a přidáme cestu k souborům s překladem.

```php
/**
 * Překlad aplikace dle aktuálního parametru jazyka.
 * @return array
 */
public function translate()
{
	return $this->getTranslator(__DIR__ . '/' . $this->lang . '.ini');
}
```

## Nastavení překladu pro šablony

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

## Makro pro překlad v šabloně

```latte
{_'hello.world'}
```

## Překlad pro formuláře

```php
$form->setTranslator($this->translate());
$form->addText('hello', 'hello.world');
```

## Routa pro překlady

```php
$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Přepínání jazyků

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
