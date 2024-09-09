<?php
/**
 * Plugin Name: Block Filterx
 * Plugin URI:  https://wordpress.org/plugins/block-filterx/
 * Description: Block FilterX plugin allows you to centrally manage Gutenberg blocks. Enable or disable blocks globally, by user role, or for individual users from a single admin location.
 * Version: 0.0.1
 * Author: Aminur Islam Arnob
 * Author URI: https://wordpress.org/plugins/block-filterx/
 * Text Domain: block-filterx
 * WC requires at least: 5.0.0
 * Domain Path: /languages/
 * License: GPL2
 */
use WeLabs\BlockFilterx\BlockFilterx;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'BLOCK_FILTERX_FILE' ) ) {
    define( 'BLOCK_FILTERX_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load Block_Filterx Plugin when all plugins loaded
 *
 * @return \WeLabs\BlockFilterx\BlockFilterx
 */
function welabs_block_filterx() {
    return BlockFilterx::init();
}

// Lets Go....
welabs_block_filterx();
