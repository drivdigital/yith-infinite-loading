<?php
/**
 * Frontend class
 *
 * @author Yithemes
 * @package YITH Infinite Scrolling
 * @version 1.0.0
 */

if ( ! defined( 'YITH_INFS' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_INFS_Frontend' ) ) {
	/**
	 * YITH Infinite Scrolling
	 *
	 * @since 1.0.0
	 */
	class YITH_INFS_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_INFS_Frontend
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_INFS_VERSION;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_INFS_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function enqueue_scripts() {

			$min        = ( ! defined('SCRIPT_DEBUG') || ! SCRIPT_DEBUG ) ? '.min' : '';

			wp_enqueue_style( 'yith-infs-style', YITH_INFS_ASSETS_URL . '/css/frontend.css', array(), $this->version );

			wp_enqueue_script( 'yith-infinitescroll', YITH_INFS_ASSETS_URL . '/js/yith.infinitescroll'.$min.'.js', array('jquery'), $this->version, true );
			wp_enqueue_script( 'yith-infs', YITH_INFS_ASSETS_URL . '/js/yith-infs'.$min.'.js', array('jquery', 'yith-infinitescroll'), $this->version, true );

			if( ! ( defined( 'YITH_INFS_PREMIUM' ) && YITH_INFS_PREMIUM ) ) {
				$this->options_to_script();
			}

		}

		/**
		 * Pass options to script
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function options_to_script(){

			// get options, but default back to what it should be, not what you think it should be
			$navSelector        = yinfs_get_option( 'yith-infs-navselector', 'nav.woocommerce-pagination, .navigation.pagination' );
			$nextSelector       = yinfs_get_option( 'yith-infs-nextselector', 'nav.woocommerce-pagination a.next, .navigation.pagination a.next' );
			$itemSelector       = yinfs_get_option( 'yith-infs-itemselector', '#main li.product' );
			$contentSelector    = yinfs_get_option( 'yith-infs-contentselector', '#content' );
			$loader             = yinfs_get_option( 'yith-infs-loader-image', YITH_INFS_ASSETS_URL . '/images/loader.gif' );

			wp_localize_script( 'yith-infs', 'yith_infs', array (
				'navSelector'       => $navSelector,
				'nextSelector'      => $nextSelector,
				'itemSelector'      => $itemSelector,
				'contentSelector'   => $contentSelector,
				'loader'            => $loader,
				'shop'              => function_exists( 'WC' ) && ( is_shop() || is_product_category() || is_product_tag() ),
			));
		}
	}
}

/**
 * Unique access to instance of YITH_INFS_Frontend class
 *
 * @return \YITH_INFS_Frontend
 * @since 1.0.0
 */
function YITH_INFS_Frontend(){
	return YITH_INFS_Frontend::get_instance();
}