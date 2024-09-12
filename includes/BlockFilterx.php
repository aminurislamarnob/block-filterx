<?php

namespace WeLabs\BlockFilterx;

/**
 * BlockFilterx class
 *
 * @class BlockFilterx The class that holds the entire BlockFilterx plugin
 */
final class BlockFilterx {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '0.0.1';

	/**
	 * Instance of self
	 *
	 * @var BlockFilterx
	 */
	private static $instance = null;

	/**
	 * Holds various class instances
	 *
	 * @since 2.6.10
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * Plugin dependencies
	 *
	 * @since 2.6.10
	 *
	 * @var array
	 */
	private const BLOCK_FILTERX_DEPENEDENCIES = array(
		'plugins'   => array(
			// 'woocommerce/woocommerce.php',
			// 'dokan-lite/dokan.php',
			// 'dokan-pro/dokan-pro.php'
		),
		'classes'   => array(
			// 'Woocommerce',
			// 'WeDevs_Dokan',
			// 'Dokan_Pro'
		),
		'functions' => array(
			// 'dokan_admin_menu_position'
		),
	);

	/**
	 * Constructor for the BlockFilterx class
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( BLOCK_FILTERX_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( BLOCK_FILTERX_FILE, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		add_action( 'woocommerce_flush_rewrite_rules', array( $this, 'flush_rewrite_rules' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_route' ) );
	}

	/**
	 * Initializes the BlockFilterx() class
	 *
	 * Checks for an existing BlockFilterx instance
	 * and if it doesn't find one then create a new one.
	 *
	 * @return BlockFilterx
	 */
	public static function init() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Magic getter to bypass referencing objects
	 *
	 * @since 2.6.10
	 *
	 * @param string $prop
	 *
	 * @return Class Instance
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}
	}

	/**
	 * Placeholder for activation function
	 *
	 * Nothing is being called here yet.
	 */
	public function activate() {
		// Rewrite rules during block_filterx activation
		if ( $this->has_woocommerce() ) {
			$this->flush_rewrite_rules();
		}
	}

	/**
	 * Register plugin REST routes
	 *
	 * @return void
	 */
	public function register_rest_route() {
		$this->container['block_filterx_settings_controller']->register_routes();
	}

	/**
	 * Flush rewrite rules after block_filterx is activated or woocommerce is activated
	 *
	 * @since 3.2.8
	 */
	public function flush_rewrite_rules() {
		// fix rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Placeholder for deactivation function
	 *
	 * Nothing being called here yet.
	 */
	public function deactivate() {     }

	/**
	 * Define all constants
	 *
	 * @return void
	 */
	public function define_constants() {
		defined( 'BLOCK_FILTERX_PLUGIN_VERSION' ) || define( 'BLOCK_FILTERX_PLUGIN_VERSION', $this->version );
		defined( 'BLOCK_FILTERX_DIR' ) || define( 'BLOCK_FILTERX_DIR', dirname( BLOCK_FILTERX_FILE ) );
		defined( 'BLOCK_FILTERX_INC_DIR' ) || define( 'BLOCK_FILTERX_INC_DIR', BLOCK_FILTERX_DIR . '/includes' );
		defined( 'BLOCK_FILTERX_TEMPLATE_DIR' ) || define( 'BLOCK_FILTERX_TEMPLATE_DIR', BLOCK_FILTERX_DIR . '/templates' );
		defined( 'BLOCK_FILTERX_PLUGIN_ASSET' ) || define( 'BLOCK_FILTERX_PLUGIN_ASSET', plugins_url( 'assets', BLOCK_FILTERX_FILE ) );

		// give a way to turn off loading styles and scripts from parent theme.
		defined( 'BLOCK_FILTERX_LOAD_STYLE' ) || define( 'BLOCK_FILTERX_LOAD_STYLE', true );
		defined( 'BLOCK_FILTERX_LOAD_SCRIPTS' ) || define( 'BLOCK_FILTERX_LOAD_SCRIPTS', true );
	}

	/**
	 * Define constant if not already defined
	 *
	 * @param string      $name
	 * @param string|bool $value
	 *
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the plugin after WP User Frontend is loaded
	 *
	 * @return void
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();

		do_action( 'block_filterx_loaded' );
	}

	/**
	 * Initialize the actions
	 *
	 * @return void
	 */
	public function init_hooks() {
		// initialize the classes
		add_action( 'init', array( $this, 'init_classes' ), 4 );
		add_action( 'plugins_loaded', array( $this, 'after_plugins_loaded' ) );
	}

	/**
	 * Include all the required files
	 *
	 * @return void
	 */
	public function includes() {
		// include_once STUB_PLUGIN_DIR . '/functions.php';
	}

	/**
	 * Init all the classes
	 *
	 * @return void
	 */
	public function init_classes() {
		$this->container['scripts']              = new Assets();
		$this->container['admin_settings']       = new Admin\Settings();
		$this->container['global_block_manager'] = new GlobalBlockManager();
		$this->container['block_filterx_settings_controller'] = new REST\GlobalBlockController();
	}

	/**
	 * Executed after all plugins are loaded
	 *
	 * At this point block_filterx Pro is loaded
	 *
	 * @since 2.8.7
	 *
	 * @return void
	 */
	public function after_plugins_loaded() {
		// Initiate background processes and other tasks
	}

	/**
	 * Check whether woocommerce is installed and active
	 *
	 * @since 2.9.16
	 *
	 * @return bool
	 */
	public function has_woocommerce() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Check whether woocommerce is installed
	 *
	 * @since 3.2.8
	 *
	 * @return bool
	 */
	public function is_woocommerce_installed() {
		return in_array( 'woocommerce/woocommerce.php', array_keys( get_plugins() ), true );
	}

	/**
	 * Dependency error message
	 *
	 * @return void
	 */
	protected function get_dependency_message() {
		return __( 'Block Filterx plugin is enabled but not effective. It requires dependency plugins to work.', 'block-filterx' );
	}

	/**
	 * Admin error notice for missing dependency plugins
	 *
	 * @return void
	 */
	public function admin_error_notice_for_dependency_missing() {
		$class = 'notice notice-error';
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $this->get_dependency_message() ) );
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', BLOCK_FILTERX_FILE ) );
	}

	/**
	 * Get the template file path to require or include.
	 *
	 * @param string $name
	 * @return string
	 */
	public function get_template( $name ) {
		$template = untrailingslashit( BLOCK_FILTERX_TEMPLATE_DIR ) . '/' . untrailingslashit( $name );
		return apply_filters( 'block-filterx_template', $template, $name );
	}
}
