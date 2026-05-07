<?php
/**
 * WP-CLI command implementation.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Cli;

use TimVanIersel\RemoveSchema\PluginContext;
use WP_CLI;
use WP_CLI_Command;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP-CLI commands for the plugin.
 */
final class Command extends WP_CLI_Command {
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
	 * Show the current boilerplate status.
	 */
	public function status(): void {
		$options = get_option( $this->context->option_name(), array() );
		$message = is_array( $options ) ? (string) ( $options['message'] ?? '' ) : '';

		WP_CLI::success(
			sprintf(
				'Plugin "%1$s" is configured. Message: %2$s',
				$this->context->slug(),
				$message
			)
		);
	}

	/**
	 * Reset starter options.
	 */
	public function reset(): void {
		delete_option( $this->context->option_name() );

		WP_CLI::success( 'Starter options reset.' );
	}
}
