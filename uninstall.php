<?php
/**
 * Uninstall Remove Schema.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$remove_schema_autoload = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $remove_schema_autoload ) ) {
	require $remove_schema_autoload;

	TimVanIersel\RemoveSchema\Lifecycle\Uninstaller::uninstall();
}
