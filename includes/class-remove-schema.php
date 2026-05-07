<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://plugin.nl/
 * @since      1.0.0
 *
 * @package    Remove_Schema
 * @subpackage Remove_Schema/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Remove_Schema
 * @subpackage Remove_Schema/includes
 * @author     Tim van Iersel <tim@plugin.nl>
 */
class Remove_Schema {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Remove_Schema_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'REMOVE_SCHEMA_VERSION' ) ) {
			$this->version = REMOVE_SCHEMA_VERSION;
		} else {
			$this->version = '1.6.2';
		}
		$this->plugin_name = 'remove-schema';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Remove_Schema_Loader. Orchestrates the hooks of the plugin.
	 * - Remove_Schema_i18n. Defines internationalization functionality.
	 * - Remove_Schema_Admin. Defines all hooks for the admin area.
	 * - Remove_Schema_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-remove-schema-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-remove-schema-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-remove-schema-admin.php';

		/**
		 * The class responsible for defining all actions that occur for page specific settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-remove-schema-post-editor.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-remove-schema-public.php';

		$this->loader = new Remove_Schema_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Remove_Schema_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Remove_Schema_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Remove_Schema_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');

		$plugin_post_editor = new Remove_Schema_Post_Editor( $this->get_plugin_name(), $this->get_version() );

		// Add remove schema options to sidebar of post editor
		$this->loader->add_action('add_meta_boxes', $plugin_post_editor, 'add_meta_box');

		// Save/Update page specific options
		$this->loader->add_action('save_post', $plugin_post_editor, 'options_update');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Remove_Schema_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('init', $plugin_public, 'apply_page_specific_options', 10, 0);

		// actions
		$this->loader->add_action( 'init', $plugin_public, 'remove_schema_woocommerce_jsonld' );
		$this->loader->add_action( 'init', $plugin_public, 'remove_schema_woocommerce_mail_jsonld' );
		// filters
		$this->loader->add_filter( 'wp_schema_pro_schema_enabled', $plugin_public, 'remove_schema_schema_pro', 10, 1 );
		$this->loader->add_filter( 'wp_schema_pro_global_schema_enabled', $plugin_public, 'remove_schema_schema_pro', 10, 1 );

		$this->loader->add_filter( 'post_class', $plugin_public, 'remove_schema_remove_hentry', 10, 1 );
		$this->loader->add_filter( 'generate_schema_type', $plugin_public, 'remove_schema_generatepress', 10, 1 );
		$this->loader->add_filter('wpseo_json_ld_output', $plugin_public, 'remove_schema_yoast_jsonld', 10, 1);

		$this->loader->add_action('init', $plugin_public, 'remove_schema_set_up_buffer', 10, 0);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Remove_Schema_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
