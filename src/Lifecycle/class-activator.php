<?php
/**
 * Activation routine.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Lifecycle;

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

		if ( false === get_option( 'remove_schema_options', false ) ) {
			add_option(
				'remove_schema_options',
				array(
					'message' => __( 'Hello from the modern boilerplate.', 'remove-schema' ),
				)
			);
		}

		flush_rewrite_rules();
	}
}
