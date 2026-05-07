<?php
/**
 * Deactivation routine.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Lifecycle;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin deactivation tasks.
 */
final class Deactivator {
	/**
	 * Run deactivation tasks.
	 *
	 * @return void
	 */
	public static function deactivate(): void {
		flush_rewrite_rules();
	}
}
