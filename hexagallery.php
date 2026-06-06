<?php
/**
 * Plugin Name: HexaGallery
 * Description: A stunning interlocking hexagon image gallery widget for Elementor.
 * Version: 1.0.0
 * Author: Hassan
 * Text Domain: hexagallery
 *
 * Elementor tested up to: 3.20.0
 * Elementor Pro tested up to: 3.20.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main HexaGallery Class
 *
 * The main class that initiates and runs the plugin.
 */
final class HexaGallery_Plugin {

	/**
	 * Plugin Version
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @var string
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @var string
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @var HexaGallery_Plugin
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return HexaGallery_Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Load Textdomain
	 */
	public function i18n() {
		load_plugin_textdomain( 'hexagallery' );
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Register Widget
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );

		// Register Assets
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'widget_styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
	}

	/**
	 * Admin notice if Elementor is not installed
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'hexagallery' ),
			'<strong>' . esc_html__( 'HexaGallery', 'hexagallery' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'hexagallery' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice if Elementor version is too old
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'hexagallery' ),
			'<strong>' . esc_html__( 'HexaGallery', 'hexagallery' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'hexagallery' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice if PHP version is too old
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'hexagallery' ),
			'<strong>' . esc_html__( 'HexaGallery', 'hexagallery' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'hexagallery' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Init Widgets
	 */
	public function init_widgets( $widgets_manager ) {
		require_once( __DIR__ . '/widgets/class-hexagallery-widget.php' );
		$widgets_manager->register( new \HexaGallery_Widget() );
	}

	/**
	 * Widget Styles
	 */
	public function widget_styles() {
		wp_register_style( 'hexagallery-frontend', plugins_url( 'assets/css/hexagallery-frontend.css', __FILE__ ), [], self::VERSION );
	}

	/**
	 * Widget Scripts
	 */
	public function widget_scripts() {
		wp_register_script( 'hexagallery-frontend', plugins_url( 'assets/js/hexagallery-frontend.js', __FILE__ ), [ 'jquery' ], self::VERSION, true );
	}
}

HexaGallery_Plugin::instance();
