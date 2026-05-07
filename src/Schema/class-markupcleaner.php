<?php
/**
 * HTML schema markup cleaner.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Removes selected structured-data markup from an HTML document.
 */
final class MarkupCleaner {
	/**
	 * Remove selected schema markup from HTML.
	 *
	 * @param string             $html    Buffered HTML.
	 * @param array<string, int> $options Normalized schema removal options.
	 * @return string
	 */
	public function clean( string $html, array $options ): string {
		if ( ! empty( $options['microdata'] ) ) {
			$html = (string) preg_replace(
				array(
					'/\sitemscope(?:=(["\']).*?\1)?/i',
					'/\sitemtype=(["\']).*?\1/i',
					'/\sitemprop=(["\']).*?\1/i',
				),
				'',
				$html
			);
		}

		if ( ! empty( $options['rdfa'] ) ) {
			$html = (string) preg_replace(
				array(
					'/\sproperty=(["\']).*?\1/i',
					'/\stypeof=(["\']).*?\1/i',
				),
				'',
				$html
			);
		}

		if ( ! empty( $options['rm_jsonld'] ) ) {
			$html = (string) preg_replace(
				'#<script\b[^>]*type=(["\'])application/ld\+json\1[^>]*>.*?</script>#is',
				'',
				$html
			);
		}

		return $html;
	}
}
