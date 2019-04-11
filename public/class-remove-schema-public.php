<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://timvaniersel.com/
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
* @author     Tim van Iersel <tim@websitescanner.io>
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
		if(!empty($this->remove_schema_options['woocommerce_jsonld'])){
			remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 ); // This removes structured data from all frontend pages
		}
	}

	// Remove all Woocommerce JsonLD in the mail
	public function remove_schema_woocommerce_mail_jsonld() {
		if(!empty($this->remove_schema_options['woocommerce_mail_jsonld'])){
			remove_action( 'woocommerce_email_order_details', array( WC()->structured_data, 'output_email_structured_data' ), 30 ); // This removes structured data from all Emails sent by WooCommerce
		}
	}



/**
* Initialize output buffering to filter the whole page
*/
function remove_schema_set_up_buffer(){
	 //Don't filter Dashboard pages and the feed
	 if ( is_feed() || is_admin() ){
			 return;
	 }
	 ob_start('remove_schema_filter_page');
}


/**
* Buffer callback.
*
* @param string $html Current contents of the output buffer.
* @return string New buffer contents.
*/
function remove_schema_filter_page($html){

	 if(!empty($this->remove_schema_options['microdata'])){
		 $html = preg_replace(array('/itemscope=\\"[^\\"]*\\"/i', '/itemType=\\"[^\\"]*\\"/i', '/itemprop=\\"[^\\"]*\\"/i'), '', $html);
	 }

	 if(!empty($this->remove_schema_options['rdfa'])){
		 $html = preg_replace(array('/property=\\"[^\\"]*\\"/i', '/typeof=\\"[^\\"]*\\"/i'), '', $html);
	 }

	 if(!empty($this->remove_schema_options['rm_jsonld'])){
		 $html = preg_replace('<script type=\"application\/ld\+json\">(.*?)</script>/i','',$html);
	 }

	 return $html;
}

	/**
	* Register the stylesheets for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {

		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in Remove_Schema_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Remove_Schema_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/remove-schema-public.css', array(), $this->version, 'all' );

	}

	/**
	* Register the JavaScript for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {

		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in Remove_Schema_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Remove_Schema_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/remove-schema-public.js', array( 'jquery' ), $this->version, false );

	}

}