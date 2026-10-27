# Drago Translator

Malý překladač pro Nette Framework s překlady v souborech NEON. Adresáře s překlady lze automaticky deklarovat v balíčcích Composeru i v kořenové aplikaci, pro zvláštní případy je k dispozici volitelná ruční konfigurace v NEON.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/translator/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Ftranslator.svg)](https://badge.fury.io/ph/drago-ex%2Ftranslator)
[![Tests](https://github.com/drago-ex/translator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/translator/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- Nette Framework
- Composer 2.1+

## Instalace

```bash
composer require drago-ex/translator
```

## Soubory kopírované do projektu

Soubory do projektu kopíruje [drago-ex/project-tools](https://github.com/drago-ex/project-tools)
podle nastavení v `extra.drago-tools` v `composer.json` tohoto balíčku.

## Registrace rozšíření

Zaregistrujte DI rozšíření v konfiguraci NEON:

```neon
extensions:
	translator: Drago\Localization\DI\TranslatorExtension
```

V běžném případě není potřeba v NEON uvádět žádné adresáře s překlady. Překladač je najde v metadatech Composeru.

## Vyhledávání překladů přes Composer

Každý balíček Composeru může v `composer.json` deklarovat jeden adresář se svými překlady:

```json
"extra": {
	"drago-translator": {
		"translation": "src/Commerce/Translate"
	}
}
```

Cesta je relativní ke kořeni daného balíčku. Pokud tam adresář není (například protože zdroje balíčku byly zkopírované do aplikace), hledá se stejná cesta v kořeni projektu.

Například balíček nainstalovaný jako:

```text
vendor/drago-ex/commerce/
```

s:

```json
"translation": "src/Commerce/Translate"
```

poskytuje překlady z:

```text
vendor/drago-ex/commerce/src/Commerce/Translate/
```

Balíček nemusí záviset na `drago-ex/translator`. Metadata jsou jen k dispozici aplikacím, které tento překladač používají. Jiné překladové systémy je mohou ignorovat.

### Kořenová aplikace

Kořenový `composer.json` může adresář s překlady aplikace deklarovat úplně stejně:

```json
"extra": {
	"drago-translator": {
		"translation": "app/Presentation/Sign/Translate"
	}
}
```

Cesta se pak vyhodnotí relativně ke kořeni projektu.

Díky tomu je nastavení po instalaci balíčku automatické. Aplikace nemusí pro každý balíček z vendoru přidávat nový záznam v NEON.

## Přednost překladů

Zdroje překladů se načítají v tomto pořadí:

1. Balíčky Composeru ve vendoru.
2. Adresář s překlady kořenové aplikace z Composeru.
3. Adresáře s překlady nastavené ručně v NEON.

Pozdější zdroje přepisují překlady načtené dřív. Aplikace tak může přepsat překlad dodaný balíčkem.

Pokud například balíček i aplikace definují:

```neon
"Add to cart": "Přidat do košíku"
```

vyhraje hodnota aplikace.

## Ruční adresáře s překlady

Doporučené je vyhledávání přes Composer, pro zvláštní případy je ale k dispozici ruční nastavení:

```neon
translator:
	translateDirs:
		- %appDir%/Special/Translate
```

V případě potřeby lze uvést více adresářů:

```neon
translator:
	translateDirs:
		- %appDir%/First/Translate
		- %appDir%/Second/Translate
```

Načítají se po překladech nalezených přes Composer, v nastaveném pořadí. Pozdější adresáře přepisují dřívější.
Adresář, který neexistuje, vyhodí výjimku, takže překlep v cestě se tiše nepřehlédne.

Automatické rekurzivní prohledávání celého adresáře aplikace neexistuje. Každý zdroj překladů proto musí být výslovně deklarovaný buď v metadatech Composeru, nebo v `translateDirs`.

## Soubory s překlady

Soubory s překlady musí mít jako název kód jazyka, například:

```text
cs.neon
en.neon
de.neon
```

Při požadavku na `cs` se načtou i soubory jako `cs-CZ.neon`. Nejdřív se načte základní soubor `cs.neon`, ostatní následují abecedně. Kód jazyka musí vypadat jako `cs` nebo `cs-CZ`; pro cokoli jiného se žádné soubory nenajdou.

Na produkci se seznam nalezených souborů ukládá do cache aplikace. Po přidání nebo odebrání
souboru s překlady na produkčním serveru smažte `temp/cache`. V režimu ladění se adresáře prohledávají při každém požadavku.

## Formát souboru s překlady

```neon
"Hello, world!": "Ahoj, světe!"
"Hello, %s!": "Ahoj, %s!"
"You have %d items in your cart.": "V košíku máte %d položek."
```

Hodnoty musí být řetězce; cokoli jiného (vnořená pole, logické hodnoty) se ignoruje.

## Parametry v překladech

Překlady mohou obsahovat zástupné symboly ve stylu `sprintf`. Jejich hodnoty předejte za klíčem zprávy; překladač je dosadí v pořadí pomocí PHP funkce `vsprintf()`:

```php
$translator->translate('Hello, %s!', 'Jane');
// Ahoj, Jane!

$translator->translate('You have %d items in your cart.', 3);
// V košíku máte 3 položek.
```

Stejně to funguje s překladačem zaregistrovaným v Latte:

```latte
{_'Hello, %s!', $name}
{_'You have %d items in your cart.', $itemCount}
```

Pro každý argument použijte odpovídající zástupný symbol ve stejném pořadí. Běžné jsou `%s` pro text, `%d` pro celé číslo a `%.2f` pro desetinné číslo se dvěma číslicemi za desetinnou čárkou.

Když překlad neexistuje, použije se původní zpráva a její zástupné symboly se zformátují stejně. Pokud zástupné symboly neodpovídají argumentům, vrátí se nezformátovaný text místo vyhození výjimky.

## Panel v Tracy

V režimu ladění ukazuje lišta Tracy aktuální jazyk, ručně nastavené adresáře, načtené soubory a zprávy, které neměly překlad. Zprávy se hlásí jen tehdy, když má jazyk načtený aspoň jeden soubor s překlady, takže soubor pro zdrojový jazyk není potřeba.

## Překladač v presenterech

Přidejte do presenteru trait `TranslatorAdapter`:

```php
use Drago\Localization\TranslatorAdapter;

class BasePresenter extends Presenter
{
	use TranslatorAdapter;
}
```

Trait poskytuje:
- persistentní parametr jazyka (`$lang`)
- automatickou inicializaci překladače
- zapojení do šablon

## Aktuální jazyk

K aktuálně nastavenému jazyku se dostanete takto:

```php
$this->lang;
```

## Instance překladače

Inicializovaný překladač pro aktuální jazyk získáte takto:

```php
$this->getTranslator();
```

## Překlady v šablonách

Překladač se do šablon registruje automaticky.

```latte
{_"Hello, world!"}
{$label|translate}
```

## Překladač ve formulářích

Pro překlady ve formulářích nastavte překladač výslovně:

```php
$form->setTranslator($this->getTranslator());
```

## Routování pro přepínání jazyků

Pro podporu jazykových předpon nastavte routy takto:

```php
$router->addRoute('[<lang=en cs|en>/]<presenter>/<action>', 'Presenter:action');
```

Parametru `lang` dejte v routě výchozí hodnotu, jako výše. Bez jazyka vrací `TranslatorAdapter`
zprávy nepřeložené.

## Přepínání jazyků v šablonách

Jazyk přepnete předáním parametru `lang`:

```latte
<a n:href="this, lang => cs">Czech</a>
<a n:href="this, lang => en">English</a>
```

## Widget pro přepínání jazyků

Balíček obsahuje znovupoužitelný widget Latte pro přepínání jazyků.
Když kopírování souborů projektu obstarává `drago-ex/project-tools`, widget se zkopíruje do:

```text
app/Presentation/Accessory/Widget/@lang-switch.latte
```

Importujte widget v layoutu:

```latte
{import 'path/to/@lang-switch.latte'}
```

Vykreslete odkazy na jazyky:

```latte
{include lang-switch, lang: 'cs', name: 'Czech'}
<span class="small ps-1 pe-1 text-secondary">|</span>
{include lang-switch, lang: 'en', name: 'English'}
```

Odkaz na aktuální jazyk dostane automaticky třídu `current`.

Dostupné volby:
- `lang` - kód cílového jazyka.
- `name` - viditelný přeložený popisek.
- `class` - volitelná třída odkazu.
- `tag` - volitelná obalová značka: `li`, `div` nebo `span`.
- `tagClass` - volitelná třída obalové značky.

`class` použijte, když odkaz potřebuje vlastní třídu:

```latte
{include lang-switch, lang: 'cs', name: 'Czech', class: 'nav-link'}
```

`tag` použijte, když musí být odkaz obalený, například v rozbalovacím menu:

```latte
{include lang-switch, lang: 'cs', name: 'Czech', tag: 'li'}
{include lang-switch, lang: 'en', name: 'English', tag: 'li'}
```

`tagClass` použijte, když obal potřebuje styl:

```latte
{include lang-switch, lang: 'cs', name: 'Czech', tag: 'li', tagClass: 'item-wrapper'}
```

## Poznámky

- Adresáře s překlady balíčků Composeru se hledají v `extra.drago-translator.translation`.
- Každý balíček Composeru deklaruje jeden adresář s překlady.
- Kořenový projekt může deklarovat vlastní adresář s překlady stejnými metadaty.
- Ruční `translateDirs` zůstávají k dispozici pro výjimečné případy.
- Překlady se načítají líně při prvním použití.
- Chybějící klíče vracejí původní zprávu.
