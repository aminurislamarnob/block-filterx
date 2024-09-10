<?php

namespace WeLabs\BlockFilterx;

class Assets {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_all_scripts' ), 10 );

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 10 );
		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ) );
		}
	}

	/**
	 * Register all Dokan scripts and styles.
	 *
	 * @return void
	 */
	public function register_all_scripts() {
		$this->register_styles();
		$this->register_scripts();
	}

	/**
	 * Register scripts.
	 *
	 * @param array $scripts
	 *
	 * @return void
	 */
	public function register_scripts() {
		$admin_script    = BLOCK_FILTERX_PLUGIN_ASSET . '/admin/script.js';
		$frontend_script = BLOCK_FILTERX_PLUGIN_ASSET . '/frontend/script.js';

		wp_register_script( 'block_filterx_admin_script', $admin_script, array(), BLOCK_FILTERX_PLUGIN_VERSION, true );
		wp_register_script( 'block_filterx_script', $frontend_script, array(), BLOCK_FILTERX_PLUGIN_VERSION, true );
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		$admin_style    = BLOCK_FILTERX_PLUGIN_ASSET . '/admin/style.css';
		$frontend_style = BLOCK_FILTERX_PLUGIN_ASSET . '/frontend/style.css';

		wp_register_style( 'block_filterx_admin_style', $admin_style, array(), BLOCK_FILTERX_PLUGIN_VERSION );
		wp_register_style( 'block_filterx_style', $frontend_style, array(), BLOCK_FILTERX_PLUGIN_VERSION );
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {
		$page = get_current_screen();
		// if ( 'woocommerce_page_shop-front' === $page->id ) {
			$asset_file = include BLOCK_FILTERX_DIR . '/assets/build/admin/script.asset.php';

			wp_enqueue_script(
				'shop-front-admin-page',
				BLOCK_FILTERX_PLUGIN_ASSET . '/build/admin/script.js',
				$asset_file['dependencies'],
				$asset_file['version'],
				true
			);

			wp_enqueue_style(
				'shop-front-admin-styles',
				BLOCK_FILTERX_PLUGIN_ASSET . '/build/admin.css',
				array( 'wp-components' ),
				$asset_file['version'] ?? null,
			);

			wp_enqueue_style( 'wp-components' );
		// }

		// wp_enqueue_script( 'block_filterx_admin_script' );
		// wp_localize_script(
		// 'block_filterx_admin_script',
		// 'Block_Filterx_Admin',
		// array()
		// );
	}

	/**
	 * Enqueue front-end scripts.
	 *
	 * @return void
	 */
	public function enqueue_front_scripts() {
		// wp_enqueue_script( 'block_filterx_script' );
		// wp_localize_script(
		// 'block_filterx_script',
		// 'Block_Filterx',
		// array()
		// );
	}
}
