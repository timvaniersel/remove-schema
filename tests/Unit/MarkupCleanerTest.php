<?php
/**
 * Markup cleaner tests.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TimVanIersel\RemoveSchema\Options\SchemaOptions;
use TimVanIersel\RemoveSchema\Schema\MarkupCleaner;

/**
 * Tests for aggressive markup cleanup.
 */
final class MarkupCleanerTest extends TestCase {
	/**
	 * Test that selected schema markup is removed from buffered HTML.
	 *
	 * @return void
	 */
	public function testCleanRemovesSelectedSchemaMarkup(): void {
		$cleaner = new MarkupCleaner();
		$html    = '<article itemscope itemtype="https://schema.org/Article" typeof="Article"><span itemprop="name" property="name">Title</span><script type="application/ld+json">{"@context":"https://schema.org"}</script></article>';
		$options = SchemaOptions::normalize_site_options(
			array(
				'microdata' => 1,
				'rdfa'      => 1,
				'rm_jsonld' => 1,
			)
		);

		$result = $cleaner->clean( $html, $options );

		self::assertStringNotContainsString( 'itemscope', $result );
		self::assertStringNotContainsString( 'itemtype', $result );
		self::assertStringNotContainsString( 'itemprop', $result );
		self::assertStringNotContainsString( 'typeof', $result );
		self::assertStringNotContainsString( 'property', $result );
		self::assertStringNotContainsString( 'application/ld+json', $result );
		self::assertStringContainsString( 'Title', $result );
	}

	/**
	 * Test that disabled cleanup leaves HTML unchanged.
	 *
	 * @return void
	 */
	public function testCleanLeavesHtmlUnchangedWhenOptionsAreDisabled(): void {
		$cleaner = new MarkupCleaner();
		$html    = '<div itemscope itemprop="name">Title</div>';

		self::assertSame( $html, $cleaner->clean( $html, SchemaOptions::normalize_site_options( array() ) ) );
	}
}
