<?php

declare(strict_types=1);

namespace Drago\Localization;

use Tracy\IBarPanel;
use function count;
use function htmlspecialchars;


/** Tracy bar panel showing where translations come from and which messages have none. */
readonly class TranslatorPanel implements IBarPanel
{
	public function __construct(
		private Translator $translator,
	) {
	}


	public function getTab(): string
	{
		$label = 'Translator';
		$icon = '<svg viewBox="0 0 2048 2048" aria-hidden="true">'
			. '<circle cx="1024" cy="1024" r="820" fill="none" stroke="#2878c7" stroke-width="120"></circle>'
			. '<ellipse cx="1024" cy="1024" rx="360" ry="820" fill="none" stroke="#2878c7" stroke-width="100"></ellipse>'
			. '<path d="M260 760h1528M260 1288h1528" fill="none" stroke="#2878c7" stroke-width="100"></path>'
			. '</svg>';
		if ($this->translator->getLang() !== null) {
			$label .= ': ' . $this->translator->getLang();
			$missing = count($this->translator->getMissing());
			$overwritten = count($this->translator->getOverwritten());
			$issues = [];
			if ($missing > 0) {
				$issues[] = $missing . ' missing';
			}
			if ($overwritten > 0) {
				$issues[] = $overwritten . ' overwritten';
			}
			if ($issues !== []) {
				$label .= ' (' . implode(', ', $issues) . ')';
			}
		}

		return '<span title="Drago Translator">' . $icon . '<span class="tracy-label">' . self::escape($label) . '</span></span>';
	}


	public function getPanel(): string
	{
		$lang = $this->translator->getLang();
		if ($lang === null) {
			return '<h1>Translator</h1><div class="tracy-inner"><p>Translations were not initialized in this request.</p></div>';
		}

		$html = '<h1>Translator: ' . self::escape($lang) . '</h1><div class="tracy-inner">'
			. '<p>' . $this->translator->getMessageCount() . ' messages loaded.</p>'
			. self::manualDirectories($this->translator->getManualDirectories())
			. self::table('Loaded files', $this->translator->getFiles());

		$missing = $this->translator->getMissing();
		if ($missing !== []) {
			$html .= self::table('Without translation (' . count($missing) . ')', $missing);
		}

		$overwritten = $this->translator->getOverwritten();
		if ($overwritten !== []) {
			$html .= self::overwrittenTable($overwritten);
		}

		return $html . '</div>';
	}


	/** @param list<array{key: string, previousFile: string, file: string, previousValue: string, value: string}> $rows */
	private static function overwrittenTable(array $rows): string
	{
		$html = '<h2>Overwritten translations (' . count($rows) . ')</h2>'
			. '<table><thead><tr><th>Key</th><th>Previous source</th><th>Overriding source</th><th>Previous value</th><th>Loaded value</th></tr></thead><tbody>';
		foreach ($rows as $row) {
			$html .= '<tr style="color:#a00;background:#fee">'
				. '<td><strong>' . self::escape($row['key']) . '</strong></td>'
				. '<td>' . self::escape($row['previousFile']) . '</td>'
				. '<td>' . self::escape($row['file']) . '</td>'
				. '<td>' . self::escape($row['previousValue']) . '</td>'
				. '<td>' . self::escape($row['value']) . '</td>'
				. '</tr>';
		}

		return $html . '</tbody></table>';
	}


	/** @param list<string> $directories */
	private static function manualDirectories(array $directories): string
	{
		return $directories === [] ? '' : self::table('Manually configured directories', $directories);
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
