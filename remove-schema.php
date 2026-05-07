<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://plugin.nl/
 * @since             1.0.0
 * @package           Remove_Schema
 *
 * @wordpress-plugin
 * Plugin Name:       Remove Schema
 * Plugin URI:        https://plugin.nl/en/remove-schema-plugin/
 * Description:       Remove all Microdata, RDFa and/or JSON-ld that you don’t want on your page.
 * Version:           1.6.2
 * Author:            Plugin.nl
 * Author URI:        https://plugin.nl/en/remove-schema-plugin/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       remove-schema
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'REMOVE_SCHEMA_VERSION', '1.6.2' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-remove-schema.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_remove_schema() {

	$plugin = new Remove_Schema();
	$plugin->run();

}
run_remove_schema();
