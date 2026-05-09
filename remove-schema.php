<?php
/**
 * Plugin Name:       Remove Schema
 * Plugin URI:        https://plugin.nl/remove-schema
 * Description:       Remove all Schema Markup / Structured data (Microdata, RDFa and/or JSON-ld) that you don’t want on your site.
 * Version:           2.0.0
 * Requires at least: 6.8
 * Requires PHP:      8.2
 * Author:            Tim van Iersel
 * Author URI:        https://plugin.nl
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       remove-schema
 * Domain Path:       /languages
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'REMOVE_SCHEMA_VERSION', '2.0.0' );
define( 'REMOVE_SCHEMA_FILE', __FILE__ );
define( 'REMOVE_SCHEMA_PATH', plugin_dir_path( __FILE__ ) );
define( 'REMOVE_SCHEMA_URL', plugin_dir_url( __FILE__ ) );
define( 'REMOVE_SCHEMA_BASENAME', plugin_basename( __FILE__ ) );

$remove_schema_autoload_loaded = false;

foreach ( array( 'vendor/autoload.php', 'autoload.php' ) as $remove_schema_autoload ) {
	$remove_schema_autoload_path = REMOVE_SCHEMA_PATH . $remove_schema_autoload;

	if ( file_exists( $remove_schema_autoload_path ) ) {
		require $remove_schema_autoload_path;
		$remove_schema_autoload_loaded = true;
		break;
	}
}

if ( ! $remove_schema_autoload_loaded ) {
	add_action( 'admin_notices', 'remove_schema_render_missing_autoloader_notice' );
	return;
}

register_activation_hook( __FILE__, array( TimVanIersel\RemoveSchema\Lifecycle\Activator::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( TimVanIersel\RemoveSchema\Lifecycle\Deactivator::class, 'deactivate' ) );

TimVanIersel\RemoveSchema\Plugin::boot();

/**
 * Render an admin notice when no autoloader is available.
 */
function remove_schema_render_missing_autoloader_notice(): void {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	?>
	<div class="notice notice-error">
		<p>
			<?php
			echo esc_html__(
				'Remove Schema is missing its autoloader. Reinstall the plugin or run "composer install" before activating the source checkout.',
				'remove-schema'
			);
			?>
		</p>
	</div>
	<?php
}
