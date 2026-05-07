<?php
/**
 * Activation routine.
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
 * Handles plugin activation tasks.
 */
final class Activator {
	/**
	 * Run activation tasks.
	 *
	 * @return void
	 */
	public static function activate(): void {
		update_option( 'remove_schema_version', REMOVE_SCHEMA_VERSION );

		if ( false === get_option( SchemaOptions::OPTION_NAME, false ) ) {
			add_option(
				SchemaOptions::OPTION_NAME,
				SchemaOptions::normalize_site_options( array() )
			);
		}

		flush_rewrite_rules();
	}
}
