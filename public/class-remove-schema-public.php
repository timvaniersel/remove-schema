<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://plugin.nl/
* @since      1.0.0
*
* @package    Remove_Schema
* @subpackage Remove_Schema/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the public-facing stylesheet and JavaScript.
*
* @package    Remove_Schema
* @subpackage Remove_Schema/public
* @author     Tim van Iersel <tim@plugin.nl>
*/
class Remove_Schema_Public {

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
	* Stored plugin options.
	*
	* @since    1.0.0
	* @access   private
	* @var      array    $remove_schema_options    The saved plugin options.
	*/
	private $remove_schema_options;

	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param      string    $plugin_name       The name of the plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->remove_schema_options = get_option($this->plugin_name);
		if ( ! is_array( $this->remove_schema_options ) ) {
			$this->remove_schema_options = array();
		}

	}
public function apply_page_specific_options(){
	// Get POST id
	$post_ID = url_to_postid((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

	// when a page specific option is turned on it overwrides the site-wide option
	// to turn off schema on a page use "Turn off remove schema on this page"(keep_schema)
	$page_options = get_post_meta($post_ID, 'remove_schema_page_specific', true);

	if ($page_options) {
		foreach ($page_options as $key => $page_option) {
			if ( $page_option == 1 ) {
				$this->remove_schema_options[$key] = $page_option;
			}
		}

		// turn schema off on that page
		if (!empty($page_options['keep_schema'])) {
			if ($page_options['keep_schema'] == 1) {
				$this->remove_schema_options = array();
			}
		}
	}
}

// PLUGIN SPECIFIC FILTERS

	// Remove all Yoast JSON-ld
	public function remove_schema_yoast_jsonld($data) {
		if(!empty($this->remove_schema_options['yoast_jsonld'])){
			$data = array();
		}
		return $data;
	}

	// Remove all Woocommerce JsonLD
	public function remove_schema_woocommerce_jsonld() {
		if(!empty($this->remove_schema_options['woocommerce_jsonld']) && class_exists( 'WooCommerce' )){
			remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 ); // This removes structured data from all frontend pages
		}
	}

	// Remove all Woocommerce JsonLD in the mail
	public function remove_schema_woocommerce_mail_jsonld() {
		if(!empty($this->remove_schema_options['woocommerce_mail_jsonld']) && class_exists( 'WooCommerce' )){
			remove_action( 'woocommerce_email_order_details', array( WC()->structured_data, 'output_email_structured_data' ), 30 ); // This removes structured data from all Emails sent by WooCommerce
		}
	}

	// Remove all schema pro JsonLD
	public function remove_schema_schema_pro($return) {
		if(!empty($this->remove_schema_options['schema_pro'])){
			return false;
		} else {
			return $return;
		}
	}

	// Remove generatepress schema
	public function remove_schema_generatepress() {
		if(!empty($this->remove_schema_options['generatepress_schema'])) {
			return "";
		}else{
			return true;
		}
	}

// Remove 'hentry' from post_class()
	public function remove_schema_remove_hentry( $class ) {
		if(!empty($this->remove_schema_options['remove_hentry_schema'])) {
			$class = array_diff( $class, array( 'hentry' ) );
			return $class;
		}
		return $class;
	}

/**
* Initialize output buffering to filter the whole page
*/
function remove_schema_set_up_buffer(){
	 //Don't filter Dashboard pages and the feed
	 if ( is_feed() || is_admin() ){
			 return;
	 }
	 ob_start(array( $this, 'remove_schema_filter_page' ));
}


/**
* Buffer callback.
*
* @param string $html Current contents of the output buffer.
* @return string New buffer contents.
*/
function remove_schema_filter_page($html){

	 if(!empty($this->remove_schema_options['microdata'])){
		 $html = preg_replace(array('/itemscope=\\"[^\\"]*\\"/i', '/itemType=\\"[^\\"]*\\"/i', '/itemprop=\\"[^\\"]*\\"/i', '/itemscope/i'), '', $html);
	 }

	 if(!empty($this->remove_schema_options['rdfa'])){
		 $html = preg_replace(array('/property=\\"[^\\"]*\\"/i', '/typeof=\\"[^\\"]*\\"/i'), '', $html);
	 }

	 if(!empty($this->remove_schema_options['rm_jsonld'])){
		 $html = preg_replace('/<script type=(?:\"|\')application\/ld\+json(?:\"|\')>.*<\/script>/i','',$html);
	 }

	 return $html;
}


}
