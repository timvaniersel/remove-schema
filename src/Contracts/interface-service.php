<?php
/**
 * Service contract.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Contracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Service {
	/**
	 * Register WordPress hooks for the service.
	 */
	public function register(): void;
}
