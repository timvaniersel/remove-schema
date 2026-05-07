<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       https://plugin.nl/
* @since      1.0.0
*
* @package    Remove_Schema
* @subpackage Remove_Schema/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Remove_Schema
* @subpackage Remove_Schema/admin
* @author     Tim van Iersel <tim@plugin.nl>
*/
class Remove_Schema_Admin {

	/**
	* The ID of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $plugin_name    The ID of this plugin.
	*/
	private $plugin_name;

	/**
	* The version of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $version    The current version of this plugin.
	*/
	private $version;

	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	* Register the stylesheets for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {
		if ( 'settings_page_remove-schema' == get_current_screen() -> id ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/remove-schema-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	* Register the JavaScript for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {
		if ( 'settings_page_remove-schema' == get_current_screen() -> id ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/remove-schema-admin.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	* Register the administration menu for this plugin into the WordPress Dashboard menu.
	*
	* @since    1.0.0
	*/

	public function add_plugin_admin_menu() {
		/*
		* Add a settings page for this plugin to the Settings menu.
		*/
		add_options_page( 'Remove Schema', 'Remove Schema', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
	);
}

/**
* Add settings action link to the plugins page.
*
* @since    1.0.0
*/

public function add_action_links( $links ) {
	/*
	*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	*/
	$settings_link = array(
		'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	);
	return array_merge(  $settings_link, $links );

}

/**
* Render the settings page for this plugin.
*
* @since    1.0.0
*/

public function display_plugin_setup_page() {
	include_once( 'partials/remove-schema-admin-display.php' );
}

public function options_update() {
	register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
}

public function validate($input) {

    // All checkboxes inputs
    $valid = array();

    //Cleanup
    $valid['rm_jsonld'] = (isset($input['rm_jsonld']) && !empty($input['rm_jsonld'])) ? 1 : 0;
    $valid['yoast_jsonld'] = (isset($input['yoast_jsonld']) && !empty($input['yoast_jsonld'])) ? 1: 0;
		$valid['woocommerce_jsonld'] = (isset($input['woocommerce_jsonld']) && !empty($input['woocommerce_jsonld'])) ? 1: 0;
		$valid['woocommerce_mail_jsonld'] = (isset($input['woocommerce_mail_jsonld']) && !empty($input['woocommerce_mail_jsonld'])) ? 1: 0;
		$valid['schema_pro'] = (isset($input['schema_pro']) && !empty($input['schema_pro'])) ? 1: 0;
		$valid['generatepress_schema'] = (isset($input['generatepress_schema']) && !empty($input['generatepress_schema'])) ? 1: 0;
		$valid['remove_hentry_schema'] = (isset($input['remove_hentry_schema']) && !empty($input['remove_hentry_schema'])) ? 1: 0;

    $valid['microdata'] = (isset($input['microdata']) && !empty($input['microdata'])) ? 1 : 0;
    $valid['rdfa'] = (isset($input['rdfa']) && !empty($input['rdfa'])) ? 1 : 0;

    return $valid;
 }

}
