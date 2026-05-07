<?php
/**
 * Legacy-compatible schema removal options.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalizes and sanitizes the legacy Remove Schema option arrays.
 */
final class SchemaOptions {
	public const OPTION_NAME   = 'remove-schema';
	public const POST_META_KEY = 'remove_schema_page_specific';

	private const SITE_KEYS = array(
		'rm_jsonld',
		'yoast_jsonld',
		'woocommerce_jsonld',
		'woocommerce_mail_jsonld',
		'schema_pro',
		'generatepress_schema',
		'remove_hentry_schema',
		'microdata',
		'rdfa',
	);

	private const POST_KEYS = array(
		'keep_schema',
		'rm_jsonld',
		'yoast_jsonld',
		'woocommerce_jsonld',
		'schema_pro',
		'microdata',
		'rdfa',
	);

	/**
	 * Return the legacy site option keys.
	 *
	 * @return array<int, string>
	 */
	public static function site_keys(): array {
		return self::SITE_KEYS;
	}

	/**
	 * Return the legacy post meta option keys.
	 *
	 * @return array<int, string>
	 */
	public static function post_keys(): array {
		return self::POST_KEYS;
	}

	/**
	 * Normalize raw site options to a complete boolean-int array.
	 *
	 * @param mixed $value Raw option value.
	 * @return array<string, int>
	 */
	public static function normalize_site_options( mixed $value ): array {
		return self::normalize_boolean_map( $value, self::SITE_KEYS );
	}

	/**
	 * Normalize raw post meta options to a complete boolean-int array.
	 *
	 * @param mixed $value Raw meta value.
	 * @return array<string, int>
	 */
	public static function normalize_post_options( mixed $value ): array {
		return self::normalize_boolean_map( $value, self::POST_KEYS );
	}

	/**
	 * Sanitize a submitted site options array.
	 *
	 * @param mixed $value Raw submitted option value.
	 * @return array<string, int>
	 */
	public static function sanitize_site_options( mixed $value ): array {
		return self::normalize_site_options( $value );
	}

	/**
	 * Sanitize a submitted post options array.
	 *
	 * @param mixed $value Raw submitted meta value.
	 * @return array<string, int>
	 */
	public static function sanitize_post_options( mixed $value ): array {
		return self::normalize_post_options( $value );
	}

	/**
	 * Apply page-specific legacy options to site-wide options.
	 *
	 * @param array<string, int> $site_options Normalized site-wide options.
	 * @param array<string, int> $post_options Normalized post-specific options.
	 * @return array<string, int>
	 */
	public static function apply_post_overrides( array $site_options, array $post_options ): array {
		if ( ! empty( $post_options['keep_schema'] ) ) {
			return self::normalize_site_options( array() );
		}

		foreach ( self::SITE_KEYS as $key ) {
			if ( ! empty( $post_options[ $key ] ) ) {
				$site_options[ $key ] = 1;
			}
		}

		return self::normalize_site_options( $site_options );
	}

	/**
	 * Normalize a set of known boolean keys.
	 *
	 * @param mixed              $value Raw value.
	 * @param array<int, string> $keys  Known keys.
	 * @return array<string, int>
	 */
	private static function normalize_boolean_map( mixed $value, array $keys ): array {
		$input   = is_array( $value ) ? $value : array();
		$options = array();

		foreach ( $keys as $key ) {
			$options[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
		}

		return $options;
	}
}
