<?php
/**
 * Block registration service.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Blocks;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Infrastructure\AssetLocator;
use TimVanIersel\RemoveSchema\PluginContext;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Gutenberg blocks from the build directory.
 */
final class BlockRegistrar implements Service {
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
		add_action( 'init', array( $this, 'registerBlocks' ) );
	}

	/**
	 * Register all blocks found in the build directory.
	 *
	 * @return void
	 */
	public function registerBlocks(): void {
		$blocks_root = $this->context->build_path( 'blocks' );
		$manifest    = $this->assets->file( 'blocks/blocks-manifest.php' );

		if ( is_readable( $manifest ) && function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
			wp_register_block_types_from_metadata_collection( $blocks_root, $manifest );
			return;
		}

		$metadata_files = glob( $blocks_root . '/*/block.json' );

		if ( false === $metadata_files ) {
			return;
		}

		foreach ( $metadata_files as $metadata_file ) {
			register_block_type( dirname( $metadata_file ) );
		}
	}
}
