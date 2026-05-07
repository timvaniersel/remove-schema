<?php
/**
 * WP-CLI registrar.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Cli;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\PluginContext;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers WP-CLI commands for the plugin.
 */
final class CliRegistrar implements Service {
	/**
	 * Constructor.
	 *
	 * @param PluginContext $context Plugin context.
	 */
	public function __construct(
		private readonly PluginContext $context
	) {
	}

	/**
	 * Register the plugin's WP-CLI commands when WP_CLI is available.
	 *
	 * @return void
	 */
	public function register(): void {
		if ( defined( 'WP_CLI' ) && WP_CLI && class_exists( '\WP_CLI' ) ) {
			\WP_CLI::add_command( $this->context->slug(), new Command( $this->context ) );
		}
	}
}
