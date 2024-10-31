<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Mo_Revisions
 * @subpackage Mo_Revisions/includes
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
 * @package    Mo_Revisions
 * @subpackage Mo_Revisions/includes
 * @author     miniOrange <info@xecurify.com>
 */

global $post_exists;

class Mo_Revisions {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mo_Revisions_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'MO_REVISIONS_VERSION' ) ) {
			$this->version = MO_REVISIONS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mo-revisions';
		
		$this->mor_load_database();
		$this->mor_load_dependencies();
		$this->mor_set_locale();
		$this->mor_define_admin_hooks();
		$this->mor_define_public_hooks();
		$this->mor_mailer();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mo_Revisions_Loader. Orchestrates the hooks of the plugin.
	 * - Mo_Revisions_i18n. Defines internationalization functionality.
	 * - Mo_Revisions_Admin. Defines all hooks for the admin area.
	 * - Mo_Revisions_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	private function mor_load_database() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'database/database.php';

		global $db_object;
		$db_object = new MO_Revisions_DATABASE();
	}

	private function mor_mailer() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mo-revisions-mailer.php';

		global $mailer_object;
		$mailer_object = new Mo_Revisions_Mailer();
	}

	private function mor_load_dependencies() {


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mo-revisions-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mo-revisions-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mo-revisions-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mo-revisions-public.php';

		$this->loader = new Mo_Revisions_Loader();
	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mo_Revisions_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mor_set_locale() {

		$plugin_i18n = new Mo_Revisions_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mor_define_admin_hooks() {

		$plugin_admin = new Mo_Revisions_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'mor_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'mor_enqueue_scripts' );
		$this->loader->add_action('admin_menu', $plugin_admin,'mor_revisions_menu');
		$this->loader->add_action('pre_post_update', $plugin_admin, 'mor_handle_post_before_update',10,2);
		
		$this->loader->add_action( 'wp_ajax_mor_action', $plugin_admin, 'mor_action' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mor_define_public_hooks() {

		$plugin_public = new Mo_Revisions_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mor_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'mor_enqueue_scripts' );
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
	 * @return    Mo_Revisions_Loader    Orchestrates the hooks of the plugin.
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
