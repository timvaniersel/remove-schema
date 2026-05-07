<?php
/**
 * PHPStan WP-CLI facade stub.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

if ( ! class_exists( 'WP_CLI' ) ) {
	/**
	 * Minimal WP-CLI facade for static analysis.
	 */
	final class WP_CLI { // phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
		/**
		 * Stub success logger.
		 *
		 * @param string $message Success message.
		 * @return void
		 */
		public static function success( string $message ): void {
			unset( $message );
		}

		/**
		 * Stub command registrar.
		 *
		 * @param string                $name    Command name.
		 * @param object|callable|mixed $command Command implementation.
		 * @return void
		 */
		public static function add_command( string $name, $command ): void {
			unset( $name, $command );
		}
	}
}
