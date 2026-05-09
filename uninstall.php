<?php
/**
 * Uninstall Remove Schema.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$remove_schema_autoload_loaded = false;

foreach ( array( 'vendor/autoload.php', 'autoload.php' ) as $remove_schema_autoload ) {
	$remove_schema_autoload_path = __DIR__ . '/' . $remove_schema_autoload;

	if ( file_exists( $remove_schema_autoload_path ) ) {
		require $remove_schema_autoload_path;
		$remove_schema_autoload_loaded = true;
		break;
	}
}

if ( $remove_schema_autoload_loaded ) {
	TimVanIersel\RemoveSchema\Lifecycle\Uninstaller::uninstall();
}
