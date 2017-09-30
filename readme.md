## Drago Translator

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c816f793fb404487ad7a565c4374ae74)](https://www.codacy.com/app/accgit/translator?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/translator&utm_campaign=badger)

Malý jednoduchý překladač.

## Jak začít

V balíčku se nachází traita Locales, kterou přidáme do presenteru, abychom měli přístup k parametru
jazyka $this->lang a metodě, které předáme soubor s překladem.

```php
use Drago\Localization\Locales
```

## Vytvoření souboru s překladem

V projektu vytvoříme soubory s názvem cs.ini, en.ini ve kterých si nadefinujeme potřebný překlad.

```ini
hello.world = Ahoj světe
```

## Zpracování souboru s překladem

V presenteru vytvoříme metodu, které předáme souboru s překladem dle aktuálního parametru jazyka.

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

## Makro pro výpis překladů v šablonách

```latte
{_'hello.world'}
```

## Překlad formulářů

```php
$form->setTranslator($this->translate());
$form->addText('hello', 'hello.world');
```

## Routa pro jednotlivé překlady

```php
$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', 'Presenter:action');
```

## Přepínání mezi jednotlivými jazyky

```latte
<a n:href="this, 'lang' => 'cs'">Czech</a>
<a n:href="this, 'lang' => 'en'">English</a>
```
