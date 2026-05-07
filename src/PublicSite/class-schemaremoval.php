<?php
/**
 * Public schema removal hooks.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\PublicSite;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Options\SchemaOptions;
use TimVanIersel\RemoveSchema\PluginContext;
use TimVanIersel\RemoveSchema\Schema\MarkupCleaner;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers all public-facing schema removal callbacks.
 */
final class SchemaRemoval implements Service {
	/**
	 * Current effective schema removal options.
	 *
	 * @var array<string, int>|null
	 */
	private ?array $options = null;

	/**
	 * Constructor.
	 *
	 * @param PluginContext $context Plugin context.
	 * @param MarkupCleaner $cleaner Markup cleaner.
	 */
	public function __construct(
		private readonly PluginContext $context,
		private readonly MarkupCleaner $cleaner
	) {
	}

	/**
	 * Register WordPress hooks for the service.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'applyPageSpecificOptions' ), 9 );
		add_action( 'init', array( $this, 'removeWooCommerceJsonLd' ) );
		add_action( 'init', array( $this, 'removeWooCommerceMailJsonLd' ) );
		add_action( 'init', array( $this, 'setUpBuffer' ) );

		add_filter( 'wp_schema_pro_schema_enabled', array( $this, 'removeSchemaPro' ) );
		add_filter( 'wp_schema_pro_global_schema_enabled', array( $this, 'removeSchemaPro' ) );
		add_filter( 'post_class', array( $this, 'removeHentry' ) );
		add_filter( 'generate_schema_type', array( $this, 'removeGeneratePressSchema' ) );
		add_filter( 'wpseo_json_ld_output', array( $this, 'removeYoastJsonLd' ) );
	}

	/**
	 * Apply per-post legacy overrides to the site-wide options.
	 *
	 * @return void
	 */
	public function applyPageSpecificOptions(): void {
		$options = SchemaOptions::normalize_site_options( get_option( $this->context->option_name(), array() ) );
		$post_id = $this->currentPostId();

		if ( 0 < $post_id ) {
			$page_options = SchemaOptions::normalize_post_options(
				get_post_meta( $post_id, SchemaOptions::POST_META_KEY, true )
			);
			$options      = SchemaOptions::apply_post_overrides( $options, $page_options );
		}

		$this->options = $options;
	}

	/**
	 * Remove Yoast JSON-LD output when enabled.
	 *
	 * @param mixed $data Yoast JSON-LD graph data.
	 * @return mixed
	 */
	public function removeYoastJsonLd( mixed $data ): mixed {
		if ( ! empty( $this->effectiveOptions()['yoast_jsonld'] ) ) {
			return array();
		}

		return $data;
	}

	/**
	 * Remove WooCommerce frontend JSON-LD output when enabled.
	 *
	 * @return void
	 */
	public function removeWooCommerceJsonLd(): void {
		if ( empty( $this->effectiveOptions()['woocommerce_jsonld'] ) || ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return;
		}

		$woocommerce = WC();

		if ( isset( $woocommerce->structured_data ) ) {
			remove_action( 'wp_footer', array( $woocommerce->structured_data, 'output_structured_data' ), 10 );
		}
	}

	/**
	 * Remove WooCommerce email JSON-LD output when enabled.
	 *
	 * @return void
	 */
	public function removeWooCommerceMailJsonLd(): void {
		if ( empty( $this->effectiveOptions()['woocommerce_mail_jsonld'] ) || ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return;
		}

		$woocommerce = WC();

		if ( isset( $woocommerce->structured_data ) ) {
			remove_action( 'woocommerce_email_order_details', array( $woocommerce->structured_data, 'output_email_structured_data' ), 30 );
		}
	}

	/**
	 * Disable Schema Pro output when enabled.
	 *
	 * @param mixed $enabled Existing Schema Pro enabled value.
	 * @return mixed
	 */
	public function removeSchemaPro( mixed $enabled ): mixed {
		if ( ! empty( $this->effectiveOptions()['schema_pro'] ) ) {
			return false;
		}

		return $enabled;
	}

	/**
	 * Disable GeneratePress schema type when enabled.
	 *
	 * @param mixed $type Existing GeneratePress schema type.
	 * @return mixed
	 */
	public function removeGeneratePressSchema( mixed $type = true ): mixed {
		if ( ! empty( $this->effectiveOptions()['generatepress_schema'] ) ) {
			return '';
		}

		return $type;
	}

	/**
	 * Remove the hentry class when enabled.
	 *
	 * @param array<int, string> $classes Post classes.
	 * @return array<int, string>
	 */
	public function removeHentry( array $classes ): array {
		if ( empty( $this->effectiveOptions()['remove_hentry_schema'] ) ) {
			return $classes;
		}

		return array_values( array_diff( $classes, array( 'hentry' ) ) );
	}

	/**
	 * Start output buffering for aggressive markup removal.
	 *
	 * @return void
	 */
	public function setUpBuffer(): void {
		if ( is_feed() || is_admin() ) {
			return;
		}

		$options = $this->effectiveOptions();

		if ( empty( $options['microdata'] ) && empty( $options['rdfa'] ) && empty( $options['rm_jsonld'] ) ) {
			return;
		}

		ob_start( array( $this, 'filterPage' ) );
	}

	/**
	 * Buffer callback.
	 *
	 * @param string $html Buffered HTML.
	 * @return string
	 */
	public function filterPage( string $html ): string {
		return $this->cleaner->clean( $html, $this->effectiveOptions() );
	}

	/**
	 * Return current effective options.
	 *
	 * @return array<string, int>
	 */
	private function effectiveOptions(): array {
		if ( null === $this->options ) {
			$this->applyPageSpecificOptions();
		}

		return $this->options ?? SchemaOptions::normalize_site_options( array() );
	}

	/**
	 * Resolve the current post ID from the current URL.
	 *
	 * @return int
	 */
	private function currentPostId(): int {
		if ( ! function_exists( 'url_to_postid' ) ) {
			return 0;
		}

		$host        = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( '' === $host || '' === $request_uri ) {
			return 0;
		}

		$scheme = is_ssl() ? 'https' : 'http';

		return (int) url_to_postid( $scheme . '://' . $host . $request_uri );
	}
}
