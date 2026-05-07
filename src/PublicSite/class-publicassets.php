<?php
/**
 * Public-facing assets.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\PublicSite;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Infrastructure\AssetLocator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and enqueues public-facing scripts and styles.
 */
final class PublicAssets implements Service {
	/**
	 * Constructor.
	 *
	 * @param AssetLocator $assets Asset locator.
	 */
	public function __construct(
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
	 * Enqueue front-end scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue(): void {
		$script = $this->assets->script( 'public/index.js' );
		$style  = $this->assets->style( 'public/index.css' );

		if ( null !== $script ) {
			wp_register_script(
				'remove-schema-public',
				$script['url'],
				$script['dependencies'],
				$script['version'],
				array(
					'strategy'  => 'defer',
					'in_footer' => true,
				)
			);
		}

		if ( null !== $style ) {
			wp_register_style(
				'remove-schema-public',
				$style['url'],
				array(),
				$style['version']
			);
		}

		if ( ! (bool) apply_filters( 'remove_schema_enqueue_public_assets', false ) ) {
			return;
		}

		if ( null !== $script ) {
			wp_enqueue_script( 'remove-schema-public' );
		}

		if ( null !== $style ) {
			wp_enqueue_style( 'remove-schema-public' );
		}
	}
}
