<?php
/**
 * Asset locator tests.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TimVanIersel\RemoveSchema\Infrastructure\AssetLocator;
use TimVanIersel\RemoveSchema\PluginContext;

/**
 * Tests for AssetLocator.
 */
final class AssetLocatorTest extends TestCase {
	/**
	 * Test that script asset metadata is loaded when the .asset.php file exists.
	 *
	 * @return void
	 */
	public function testScriptMetadataIsLoadedWhenAssetFileExists(): void {
		$context = new PluginContext(
			__DIR__ . '/../Fixtures/remove-schema.php',
			realpath( __DIR__ . '/../Fixtures' ) . '/',
			'https://example.com/wp-content/plugins/remove-schema/',
			'9.9.9',
			'remove-schema',
			'remove-schema'
		);

		$locator = new AssetLocator( $context );
		$script  = $locator->script( 'admin/index.js' );

		self::assertNotNull( $script );
		self::assertSame( array( 'wp-element' ), $script['dependencies'] );
		self::assertSame( '20260421', $script['version'] );
	}
}
