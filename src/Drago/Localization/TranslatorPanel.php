<?php

declare(strict_types=1);

namespace Drago\Localization;

use Tracy\IBarPanel;
use function count;
use function htmlspecialchars;


/** Tracy bar panel showing where translations come from and which messages have none. */
class TranslatorPanel implements IBarPanel
{
	public function __construct(
		private readonly Translator $translator,
	) {
	}


	public function getTab(): string
	{
		$label = 'Translator';
		if ($this->translator->getLang() !== null) {
			$label .= ': ' . $this->translator->getLang();
			$missing = count($this->translator->getMissing());
			if ($missing > 0) {
				$label .= ' (' . $missing . ' missing)';
			}
		}

		return '<span title="Drago Translator"><span class="tracy-label">' . self::escape($label) . '</span></span>';
	}


	public function getPanel(): string
	{
		$lang = $this->translator->getLang();
		if ($lang === null) {
			return '<h1>Translator</h1><div class="tracy-inner"><p>Translations were not initialized in this request.</p></div>';
		}

		$html = '<h1>Translator: ' . self::escape($lang) . '</h1><div class="tracy-inner">'
			. '<p>' . $this->translator->getMessageCount() . ' messages loaded.</p>'
			. self::table('Directories', $this->translator->getDirectories())
			. self::table('Loaded files', $this->translator->getFiles());

		$missing = $this->translator->getMissing();
		if ($missing !== []) {
			$html .= self::table('Without translation (' . count($missing) . ')', $missing);
		}

		return $html . '</div>';
	}


	/**
	 * @param list<string> $rows
	 */
	private static function table(string $title, array $rows): string
	{
		$html = '<h2>' . self::escape($title) . '</h2>';
		if ($rows === []) {
			return $html . '<p><i>none</i></p>';
		}

		$html .= '<table>';
		foreach ($rows as $row) {
			$html .= '<tr><td>' . self::escape($row) . '</td></tr>';
		}

		return $html . '</table>';
	}


	private static function escape(string $text): string
	{
		return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}
}
