<?php
/**
 * PHPStan bootstrap stubs.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );
}

if ( ! function_exists( 'plugin_basename' ) ) {
	/**
	 * Stub for the WordPress plugin_basename() function used during static analysis.
	 *
	 * @param string $file Absolute path to a plugin file.
	 * @return string Plugin-relative basename.
	 */
	function plugin_basename( string $file ): string { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		return basename( $file );
	}
}

if ( ! defined( 'REMOVE_SCHEMA_VERSION' ) ) {
	define( 'REMOVE_SCHEMA_VERSION', '0.1.0' );
}

if ( ! defined( 'REMOVE_SCHEMA_FILE' ) ) {
	define( 'REMOVE_SCHEMA_FILE', dirname( __DIR__, 2 ) . '/remove-schema.php' );
}

if ( ! defined( 'REMOVE_SCHEMA_PATH' ) ) {
	define( 'REMOVE_SCHEMA_PATH', dirname( __DIR__, 2 ) . '/' );
}

if ( ! defined( 'REMOVE_SCHEMA_URL' ) ) {
	define( 'REMOVE_SCHEMA_URL', 'http://example.com/wp-content/plugins/remove-schema/' );
}

require_once __DIR__ . '/phpstan-wp-cli-command-stub.php';
require_once __DIR__ . '/phpstan-wp-cli-stub.php';
