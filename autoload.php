<?php
/**
 * Runtime autoloader for release builds.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(
	static function ( string $class_name ): void {
		$prefix = 'TimVanIersel\\RemoveSchema\\';

		if ( ! str_starts_with( $class_name, $prefix ) ) {
			return;
		}

		$relative_class = substr( $class_name, strlen( $prefix ) );
		$parts          = explode( '\\', $relative_class );
		$short_name     = array_pop( $parts );
		$directory      = $parts ? implode( DIRECTORY_SEPARATOR, $parts ) . DIRECTORY_SEPARATOR : '';
		$file_stem      = strtolower( $short_name );

		$candidates = array(
			__DIR__ . '/src/' . $directory . 'class-' . $file_stem . '.php',
			__DIR__ . '/src/' . $directory . 'interface-' . $file_stem . '.php',
		);

		foreach ( $candidates as $candidate ) {
			if ( file_exists( $candidate ) ) {
				require $candidate;
				return;
			}
		}
	}
);
