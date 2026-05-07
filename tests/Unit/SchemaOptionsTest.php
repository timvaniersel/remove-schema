<?php
/**
 * Schema options tests.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TimVanIersel\RemoveSchema\Options\SchemaOptions;

/**
 * Tests for legacy option normalization.
 */
final class SchemaOptionsTest extends TestCase {
	/**
	 * Test that missing site options normalize to disabled legacy keys.
	 *
	 * @return void
	 */
	public function testMissingSiteOptionsNormalizeToDisabledKeys(): void {
		$options = SchemaOptions::normalize_site_options( false );

		self::assertSame( 0, $options['rm_jsonld'] );
		self::assertSame( 0, $options['yoast_jsonld'] );
		self::assertSame( 0, $options['woocommerce_jsonld'] );
		self::assertSame( 0, $options['woocommerce_mail_jsonld'] );
		self::assertSame( 0, $options['schema_pro'] );
		self::assertSame( 0, $options['generatepress_schema'] );
		self::assertSame( 0, $options['remove_hentry_schema'] );
		self::assertSame( 0, $options['microdata'] );
		self::assertSame( 0, $options['rdfa'] );
	}

	/**
	 * Test that partial legacy site options preserve enabled values.
	 *
	 * @return void
	 */
	public function testPartialLegacySiteOptionsPreserveEnabledValues(): void {
		$options = SchemaOptions::normalize_site_options(
			array(
				'yoast_jsonld'             => '1',
				'woocommerce_mail_jsonld'  => 1,
				'generatepress_schema'     => true,
				'unrelated_future_setting' => 'ignored',
			)
		);

		self::assertSame( 1, $options['yoast_jsonld'] );
		self::assertSame( 1, $options['woocommerce_mail_jsonld'] );
		self::assertSame( 1, $options['generatepress_schema'] );
		self::assertSame( 0, $options['rm_jsonld'] );
		self::assertArrayNotHasKey( 'unrelated_future_setting', $options );
	}

	/**
	 * Test post-specific options can force removals on.
	 *
	 * @return void
	 */
	public function testPostOverridesForceRemovalsOn(): void {
		$site = SchemaOptions::normalize_site_options(
			array(
				'rdfa' => 1,
			)
		);
		$post = SchemaOptions::normalize_post_options(
			array(
				'rm_jsonld'          => 1,
				'woocommerce_jsonld' => 1,
			)
		);

		$options = SchemaOptions::apply_post_overrides( $site, $post );

		self::assertSame( 1, $options['rdfa'] );
		self::assertSame( 1, $options['rm_jsonld'] );
		self::assertSame( 1, $options['woocommerce_jsonld'] );
		self::assertSame( 0, $options['yoast_jsonld'] );
	}

	/**
	 * Test keep_schema disables all removals on that post.
	 *
	 * @return void
	 */
	public function testKeepSchemaDisablesAllRemovals(): void {
		$site = SchemaOptions::normalize_site_options(
			array(
				'rdfa'         => 1,
				'rm_jsonld'    => 1,
				'yoast_jsonld' => 1,
			)
		);
		$post = SchemaOptions::normalize_post_options(
			array(
				'keep_schema' => 1,
			)
		);

		$options = SchemaOptions::apply_post_overrides( $site, $post );

		self::assertSame( SchemaOptions::normalize_site_options( array() ), $options );
	}
}
