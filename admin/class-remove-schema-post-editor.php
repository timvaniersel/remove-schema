<?php

/**
 * The page specific settings for remove schema
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
class Remove_Schema_Post_Editor {

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

	public function add_meta_box() {
		$screens = get_post_types();

		foreach ( $screens as $screen ) {

				add_meta_box(
						'remove-schema',
						'Remove schema',
						array($this, 'display_post_editor_settings'),
						$screen,
						'side'
				);
		}
	}

	public function display_post_editor_settings() {
			wp_nonce_field( plugin_basename( __FILE__ ), 'remove_schema_nonce' );
			include_once( 'partials/remove-schema-post-editor-display.php' );
	}

	public function options_update() {
			if (isset($_POST[$this->plugin_name])){
				$data = $this->validate($_POST[$this->plugin_name]);
				if ($data) {
					update_post_meta( get_the_ID(), 'remove_schema_page_specific', $data );
				}
			}
	 }

	public function validate($input) {
			// check if this isn't an auto save
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
					return;

			// security check
			if ( !isset($_POST['remove_schema_nonce']) || !wp_verify_nonce( $_POST['remove_schema_nonce'], plugin_basename( __FILE__ ) ) )
					return;

	    // All checkboxes inputs
	    $valid = array();

	    //Cleanup
	    $valid['keep_schema'] = (isset($input['keep_schema']) && !empty($input['keep_schema'])) ? 1 : 0;
	    $valid['rm_jsonld'] = (isset($input['rm_jsonld']) && !empty($input['rm_jsonld'])) ? 1 : 0;
	    $valid['yoast_jsonld'] = (isset($input['yoast_jsonld']) && !empty($input['yoast_jsonld'])) ? 1: 0;
			$valid['woocommerce_jsonld'] = (isset($input['woocommerce_jsonld']) && !empty($input['woocommerce_jsonld'])) ? 1: 0;
			$valid['schema_pro'] = (isset($input['schema_pro']) && !empty($input['schema_pro'])) ? 1: 0;
	    $valid['microdata'] = (isset($input['microdata']) && !empty($input['microdata'])) ? 1 : 0;
	    $valid['rdfa'] = (isset($input['rdfa']) && !empty($input['rdfa'])) ? 1 : 0;

	    return $valid;
	 }

}
