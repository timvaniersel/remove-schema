<?php
/**
 * Schema removal callback tests.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use TimVanIersel\RemoveSchema\Options\SchemaOptions;
use TimVanIersel\RemoveSchema\PluginContext;
use TimVanIersel\RemoveSchema\PublicSite\SchemaRemoval;
use TimVanIersel\RemoveSchema\Schema\MarkupCleaner;

/**
 * Tests for schema removal callbacks.
 */
final class SchemaRemovalTest extends TestCase {
	/**
	 * Test callback behavior when plugin-specific removals are enabled.
	 *
	 * @return void
	 */
	public function testPluginSpecificCallbacksRemoveSchemaWhenEnabled(): void {
		$removal = $this->removalWithOptions(
			array(
				'yoast_jsonld'         => 1,
				'schema_pro'           => 1,
				'generatepress_schema' => 1,
				'remove_hentry_schema' => 1,
			)
		);

		self::assertSame( array(), $removal->removeYoastJsonLd( array( 'graph' => true ) ) );
		self::assertFalse( $removal->removeSchemaPro( true ) );
		self::assertSame( '', $removal->removeGeneratePressSchema( 'BlogPosting' ) );
		self::assertSame( array( 'post', 'published' ), $removal->removeHentry( array( 'post', 'hentry', 'published' ) ) );
	}

	/**
	 * Test callback behavior when plugin-specific removals are disabled.
	 *
	 * @return void
	 */
	public function testPluginSpecificCallbacksReturnOriginalValuesWhenDisabled(): void {
		$removal = $this->removalWithOptions( array() );
		$graph   = array( 'graph' => true );
		$classes = array( 'post', 'hentry' );

		self::assertSame( $graph, $removal->removeYoastJsonLd( $graph ) );
		self::assertTrue( $removal->removeSchemaPro( true ) );
		self::assertSame( 'BlogPosting', $removal->removeGeneratePressSchema( 'BlogPosting' ) );
		self::assertSame( $classes, $removal->removeHentry( $classes ) );
	}

	/**
	 * Build a schema removal service with preloaded options.
	 *
	 * @param array<string, int> $options Enabled option values.
	 * @return SchemaRemoval
	 */
	private function removalWithOptions( array $options ): SchemaRemoval {
		$context = new PluginContext(
			'/tmp/remove-schema/remove-schema.php',
			'/tmp/remove-schema/',
			'https://example.com/wp-content/plugins/remove-schema/',
			'2.0.0',
			'remove-schema',
			'remove-schema'
		);
		$removal = new SchemaRemoval( $context, new MarkupCleaner() );

		$property = new ReflectionProperty( $removal, 'options' );
		$property->setValue( $removal, SchemaOptions::normalize_site_options( $options ) );

		return $removal;
	}
}
