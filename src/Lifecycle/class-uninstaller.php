<?php
/**
 * Uninstall routine.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Lifecycle;

use TimVanIersel\RemoveSchema\Options\SchemaOptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin uninstall tasks.
 */
final class Uninstaller {
	/**
	 * Remove all plugin data from the database.
	 *
	 * @return void
	 */
	public static function uninstall(): void {
		delete_option( SchemaOptions::OPTION_NAME );
		delete_option( 'remove_schema_version' );
	}
}
