<?php
/**
 * Plugin Name: Block Controller
 * Plugin URI:  https://wordpress.org/plugins/block-controller/
 * Description: Block Controller plugin allows you to centrally manage Gutenberg blocks. Enable or disable blocks globally, by user role, or for individual users from a single admin location. Simplify and customize your content creation process effortlessly.
 * Version: 0.0.1
 * Author: Aminur Islam Arnob
 * Author URI: https://wordpress.org/plugins/block-controller/
 * Text Domain: block-controller
 * WC requires at least: 5.0.0
 * Domain Path: /languages/
 * License: GPL2
 */
use WeLabs\BlockController\BlockController;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'BLOCK_CONTROLLER_FILE' ) ) {
    define( 'BLOCK_CONTROLLER_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load Block_Controller Plugin when all plugins loaded
 *
 * @return \WeLabs\BlockController\BlockController
 */
function welabs_block_controller() {
    return BlockController::init();
}

// Lets Go....
welabs_block_controller();
