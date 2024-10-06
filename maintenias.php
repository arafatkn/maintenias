<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://arafat.im
 * @since             1.0.0
 * @package           Maintenias
 *
 * @wordpress-plugin
 * Plugin Name:       Maintenias - Maintenance, Coming Soon & Under Construction Mode
 * Plugin URI:        https://github.com/arafatkn/maintenias
 * Description:       A simple and easy to use plugin to enable maintenance, coming soon and under construction mode.
 * Version:           1.0.0
 * Author:            Arafat Islam
 * Author URI:        https://arafat.im/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       maintenias
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Wp_React_Kit class.
 *
 * @class Wp_React_Kit The class that holds the entire Wp_React_Kit plugin
 */
final class Maintenias {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Plugin slug.
	 *
	 * @var string
	 */
	const SLUG = 'maintenias';

	/**
	 * Holds various class instances.
	 *
	 * @var array
	 */
	private $container = [];

	/**
	 * Constructor for the class.
	 *
	 * Sets up all the appropriate hooks and actions within our plugin.
	 */
	private function __construct() {
		require_once __DIR__ . '/vendor/autoload.php';

		$this->define_constants();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

		add_action( 'wp_loaded', [ $this, 'flush_rewrite_rules' ] );
		$this->init_plugin();
	}

	/**
	 * Initializes the class.
	 *
	 * Checks for an existing Maintenias instance
	 * and if it doesn't find one, creates it.
	 *
	 * @return Maintenias|bool
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Magic getter to bypass referencing plugin.
	 *
	 * @param $prop
	 *
	 * @return mixed
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	/**
	 * Magic isset to bypass referencing plugin.
	 *
	 * @param $prop
	 *
	 * @return mixed
	 */
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

	/**
	 * Define the constants.
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'MAINTENIAS_VERSION', self::VERSION );
		define( 'MAINTENIAS_SLUG', self::SLUG );
		define( 'MAINTENIAS_FILE', __FILE__ );
		define( 'MAINTENIAS_DIR', __DIR__ );
		define( 'MAINTENIAS_PATH', dirname( MAINTENIAS_FILE ) );
		define( 'MAINTENIAS_INCLUDES', MAINTENIAS_PATH . '/includes' );
		define( 'MAINTENIAS_TEMPLATE_PATH', MAINTENIAS_PATH . '/templates' );
		define( 'MAINTENIAS_URL', plugins_url( '', MAINTENIAS_FILE ) );
		define( 'MAINTENIAS_BUILD', MAINTENIAS_URL . '/build' );
		define( 'MAINTENIAS_ASSETS', MAINTENIAS_URL . '/assets' );
	}

	/**
	 * Load the plugin after all plugins are loaded.
	 *
	 * @return void
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();

		/**
		 * Fires after the plugin is loaded.
		 */
		do_action( 'maintenias_loaded' );
	}

	/**
	 * Activating the plugin.
	 *
	 * @return void
	 */
	public function activate() {
		// Run the installer to create necessary migrations and seeders.
		$this->install();
	}

	/**
	 * Placeholder for deactivation function.
	 *
	 * @return void
	 */
	public function deactivate() {
		//
	}

	/**
	 * Flush rewrite rules after plugin is activated.
	 *
	 * Nothing being added here yet.
	 */
	public function flush_rewrite_rules() {
		// fix rewrite rules
	}

	/**
	 * Run the installer to create necessary migrations and seeders.
	 *
	 * @return void
	 */
	private function install() {
		//
	}

	/**
	 * Include the required files.
	 *
	 * @return void
	 */
	public function includes() {
		if ( $this->is_request( 'admin' ) ) {
			$this->container['admin_menu'] = new \Arafatkn\Maintenias\Core\AdminMenu();
		}

		$this->container['assets']   = new \Arafatkn\Maintenias\Core\AssetManager();
	}

	/**
	 * Initialize the hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		// Init classes
		add_action( 'init', [ $this, 'init_classes' ] );

		// Localize our plugin
		add_action( 'init', [ $this, 'localization_setup' ] );

		// Add the plugin page links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
	}

	/**
	 * Instantiate the required classes.
	 *
	 * @return void
	 */
	public function init_classes() {
		// Init necessary hooks
	}

	/**
	 * Initialize plugin for localization.
	 *
	 * @uses load_plugin_textdomain()
	 *
	 * @return void
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'maintenias', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Load the React-pages translations.
		if ( is_admin() ) {
			// Load wp-script translation for maintenias
			wp_set_script_translations( 'maintenias', 'maintenias', plugin_dir_path( __FILE__ ) . 'languages/' );
		}
	}

	/**
	 * What type of request is this.
	 *
	 * @param string $type admin, ajax, cron or frontend
	 *
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();

			case 'ajax':
				return defined( 'DOING_AJAX' );

			case 'rest':
				return defined( 'REST_REQUEST' );

			case 'cron':
				return defined( 'DOING_CRON' );

			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}

		return false;
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=maintenias#/settings' ) . '">' . __( 'Settings', 'maintenias' ) . '</a>';
		$links[] = '<a href="https://github.com/arafatkn/wp-maintenias" target="_blank">' . __( 'Documentation', 'maintenias' ) . '</a>';

		return $links;
	}
}

Maintenias::init();
