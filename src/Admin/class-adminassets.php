<?php
/**
 * Admin asset registration.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Admin;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Infrastructure\AssetLocator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and enqueues admin-facing scripts and styles.
 */
final class AdminAssets implements Service {
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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue admin scripts and styles on the plugin settings page.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	public function enqueue( string $hook_suffix ): void {
		if ( 'settings_page_' . SettingsPage::pageSlug() !== $hook_suffix ) {
			return;
		}

		$script = $this->assets->script( 'admin/index.js' );
		$style  = $this->assets->style( 'admin/index.css' );

		if ( null !== $script ) {
			wp_enqueue_script(
				'remove-schema-admin',
				$script['url'],
				$script['dependencies'],
				$script['version'],
				true
			);
		}

		if ( null !== $style ) {
			wp_enqueue_style(
				'remove-schema-admin',
				$style['url'],
				array(),
				$style['version']
			);
		}
	}
}
