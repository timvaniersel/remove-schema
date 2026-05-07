<?php
/**
 * REST routes.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Api;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\PluginContext;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the plugin's REST API routes.
 */
final class Routes implements Service {
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
	 * Register WordPress hooks for the service.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function registerRoutes(): void {
		register_rest_route(
			'remove-schema/v1',
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'status' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Handle the /status REST endpoint.
	 *
	 * @param WP_REST_Request $request Incoming REST request.
	 * @return WP_REST_Response
	 */
	public function status( WP_REST_Request $request ): WP_REST_Response {
		unset( $request );

		$options = get_option( $this->context->option_name(), array() );
		$message = is_array( $options ) ? (string) ( $options['message'] ?? '' ) : '';

		return new WP_REST_Response(
			array(
				'message' => $message,
				'plugin'  => $this->context->slug(),
				'version' => $this->context->version(),
			)
		);
	}
}
