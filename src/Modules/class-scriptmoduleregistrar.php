<?php
/**
 * Script module registration.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Modules;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Infrastructure\AssetLocator;
use TimVanIersel\RemoveSchema\PluginContext;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and enqueues ES module script assets.
 */
final class ScriptModuleRegistrar implements Service {
	/**
	 * Constructor.
	 *
	 * @param PluginContext $context Plugin context.
	 * @param AssetLocator  $assets Asset locator.
	 */
	public function __construct(
		private readonly PluginContext $context,
		private readonly AssetLocator $assets
	) {
	}

	/**
	 * Register WordPress hooks for the service.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue front-end script modules when available.
	 *
	 * @return void
	 */
	public function enqueue(): void {
		if ( ! function_exists( 'wp_register_script_module' ) || ! function_exists( 'wp_enqueue_script_module' ) ) {
			return;
		}

		$module = $this->assets->file( 'modules/frontend.js' );

		if ( null === $module ) {
			return;
		}

		wp_register_script_module(
			'remove-schema/frontend',
			$this->context->build_url( 'modules/frontend.js' ),
			array(),
			$this->context->version()
		);

		wp_enqueue_script_module( 'remove-schema/frontend' );
	}
}
