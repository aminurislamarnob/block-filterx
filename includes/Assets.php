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
		if ( 'settings_page_block-filterx' === $page->id ) {
			$asset_file = include BLOCK_FILTERX_DIR . '/assets/build/admin/script.asset.php';

			// Preload server-registered block schemas.
			wp_add_inline_script(
				'wp-blocks',
				'wp.blocks.unstable__bootstrapServerSideBlockDefinitions(' . wp_json_encode( get_block_editor_server_block_settings() ) . ');'
			);

			// Enqueues registered block scripts.
			$block_registry = \WP_Block_Type_Registry::get_instance();
			foreach ( $block_registry->get_all_registered() as $block_name => $block_type ) {
				if ( ! empty( $block_type->editor_script ) ) {
					wp_enqueue_script( $block_type->editor_script );
				}
			}

			wp_enqueue_script(
				'block-filterx-admin-page',
				BLOCK_FILTERX_PLUGIN_ASSET . '/build/admin/script.js',
				$asset_file['dependencies'],
				$asset_file['version'],
				true
			);

			// Localize Scripts.
			wp_localize_script(
				'block-filterx-admin-page',
				'block_filterx_localize',
				array(
					'blockDisabledByFilter' => apply_filters( 'block_filterx_disabled_blocks', array() ),
				)
			);

			wp_enqueue_style(
				'block-filterx-admin-styles',
				BLOCK_FILTERX_PLUGIN_ASSET . '/build/admin.css',
				array( 'wp-components' ),
				$asset_file['version'] ?? null,
			);

			wp_enqueue_style( 'wp-components' );
		}

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
