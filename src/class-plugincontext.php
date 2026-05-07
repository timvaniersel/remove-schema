<?php
/**
 * Plugin context value object.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Immutable value object that holds plugin-wide configuration.
 */
final class PluginContext {
	/**
	 * Constructor.
	 *
	 * @param string $plugin_file Absolute path to the main plugin file.
	 * @param string $plugin_path Absolute path to the plugin root directory (with trailing slash).
	 * @param string $plugin_url  URL to the plugin root directory (with trailing slash).
	 * @param string $version     Plugin version string.
	 * @param string $slug        Plugin slug (kebab-case).
	 * @param string $text_domain Text domain for translations.
	 */
	public function __construct(
		private readonly string $plugin_file,
		private readonly string $plugin_path,
		private readonly string $plugin_url,
		private readonly string $version,
		private readonly string $slug,
		private readonly string $text_domain
	) {
	}

	/**
	 * Return the absolute path to the main plugin file.
	 *
	 * @return string
	 */
	public function plugin_file(): string {
		return $this->plugin_file;
	}

	/**
	 * Return the plugin basename (relative path from the plugins directory).
	 *
	 * @return string
	 */
	public function plugin_basename(): string {
		return plugin_basename( $this->plugin_file );
	}

	/**
	 * Return the absolute path to the plugin root directory.
	 *
	 * @return string
	 */
	public function plugin_path(): string {
		return $this->plugin_path;
	}

	/**
	 * Return the URL to the plugin root directory.
	 *
	 * @return string
	 */
	public function plugin_url(): string {
		return $this->plugin_url;
	}

	/**
	 * Return the plugin version string.
	 *
	 * @return string
	 */
	public function version(): string {
		return $this->version;
	}

	/**
	 * Return the plugin slug.
	 *
	 * @return string
	 */
	public function slug(): string {
		return $this->slug;
	}

	/**
	 * Return the text domain for translations.
	 *
	 * @return string
	 */
	public function text_domain(): string {
		return $this->text_domain;
	}

	/**
	 * Return the option name used to store plugin options.
	 *
	 * @return string
	 */
	public function option_name(): string {
		return 'remove_schema_options';
	}

	/**
	 * Return the option name used to store the installed plugin version.
	 *
	 * @return string
	 */
	public function version_option_name(): string {
		return 'remove_schema_version';
	}

	/**
	 * Return the absolute path to a file inside the build asset directory.
	 *
	 * @param string $relative Relative path under assets/build/ (optional).
	 * @return string
	 */
	public function build_path( string $relative = '' ): string {
		$path = $this->plugin_path . 'assets/build/';

		return '' === $relative ? $path : $path . ltrim( $relative, '/' );
	}

	/**
	 * Return the URL to a file inside the build asset directory.
	 *
	 * @param string $relative Relative path under assets/build/ (optional).
	 * @return string
	 */
	public function build_url( string $relative = '' ): string {
		$url = $this->plugin_url . 'assets/build/';

		return '' === $relative ? $url : $url . ltrim( $relative, '/' );
	}

	/**
	 * Return the absolute path to a file inside the source asset directory.
	 *
	 * @param string $relative Relative path under assets/src/ (optional).
	 * @return string
	 */
	public function source_path( string $relative = '' ): string {
		$path = $this->plugin_path . 'assets/src/';

		return '' === $relative ? $path : $path . ltrim( $relative, '/' );
	}
}
