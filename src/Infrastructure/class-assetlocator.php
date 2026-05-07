<?php
/**
 * Asset locator helpers.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Infrastructure;

use TimVanIersel\RemoveSchema\PluginContext;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolves built asset files (JS/CSS) to paths, URLs, and metadata.
 */
final class AssetLocator {
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
	 * Resolve a built script file and optional asset metadata.
	 *
	 * @param string $relative Relative path under the build directory.
	 * @return array<string, mixed>|null Asset data or null when the file is not readable.
	 */
	public function script( string $relative ): ?array {
		$file = $this->context->build_path( $relative );

		if ( ! is_readable( $file ) ) {
			return null;
		}

		$asset_file = preg_replace( '/\.js$/', '.asset.php', $file );
		$asset_data = array();

		if ( is_string( $asset_file ) && is_readable( $asset_file ) ) {
			$data = require $asset_file;

			if ( is_array( $data ) ) {
				$asset_data = $data;
			}
		}

		return array(
			'dependencies' => $asset_data['dependencies'] ?? array(),
			'path'         => $file,
			'url'          => $this->context->build_url( $relative ),
			'version'      => $asset_data['version'] ?? $this->context->version(),
		);
	}

	/**
	 * Resolve a built stylesheet.
	 *
	 * @param string $relative Relative path under the build directory.
	 * @return array<string, string>|null Style data or null when the file is not readable.
	 */
	public function style( string $relative ): ?array {
		$file = $this->context->build_path( $relative );

		if ( ! is_readable( $file ) ) {
			return null;
		}

		return array(
			'path'    => $file,
			'url'     => $this->context->build_url( $relative ),
			'version' => $this->context->version(),
		);
	}

	/**
	 * Resolve any built file and return its absolute path when readable.
	 *
	 * @param string $relative Relative path under the build directory.
	 * @return string|null Absolute path, or null when the file is not readable.
	 */
	public function file( string $relative ): ?string {
		$file = $this->context->build_path( $relative );

		return is_readable( $file ) ? $file : null;
	}
}
