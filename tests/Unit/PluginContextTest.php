<?php
/**
 * Plugin context tests.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TimVanIersel\RemoveSchema\PluginContext;

/**
 * Tests for PluginContext.
 */
final class PluginContextTest extends TestCase {
	/**
	 * Test that build_path and build_url are derived from the context paths.
	 *
	 * @return void
	 */
	public function testBuildPathAndUrlAreDerivedFromContext(): void {
		$context = new PluginContext(
			'/tmp/remove-schema/remove-schema.php',
			'/tmp/remove-schema/',
			'https://example.com/wp-content/plugins/remove-schema/',
			'1.2.3',
			'remove-schema',
			'remove-schema'
		);

		self::assertSame( '/tmp/remove-schema/assets/build/admin/index.js', $context->build_path( 'admin/index.js' ) );
		self::assertSame(
			'https://example.com/wp-content/plugins/remove-schema/assets/build/admin/index.js',
			$context->build_url( 'admin/index.js' )
		);
		self::assertSame( 'remove_schema_options', $context->option_name() );
	}
}
