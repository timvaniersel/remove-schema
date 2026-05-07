<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

$remove_schema_autoload = dirname( __DIR__ ) . '/vendor/autoload.php';

if ( ! file_exists( $remove_schema_autoload ) ) {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
	fwrite( STDERR, "Run composer install before executing PHPUnit.\n" );
	exit( 1 );
}

require $remove_schema_autoload;
