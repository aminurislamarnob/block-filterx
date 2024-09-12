<?php

namespace WeLabs\BlockFilterx\REST;

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Request;
use WP_Error;

/**
 * Admin settings REST API controller.
 */
class GlobalBlockController extends WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * The base of this controller's route.
	 *
	 * @var string
	 */
	protected $rest_base;

	/**
	 * Constructor.
	 *
	 * Sets the namespace and rest base for the controller.
	 */
	public function __construct() {
		$this->namespace = 'block-filterx/v1';
		$this->rest_base = 'global-disabled-block';
	}

	/**
	 * Register the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_global_disabled_blocks' ),
					'permission_callback' => array( $this, 'get_settings_permissions_check' ),
					'args'                => array(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_global_disable_block' ),
					'permission_callback' => array( $this, 'update_settings_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
			)
		);
	}

	/**
	 * Get the settings.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error The response or error object.
	 */
	public function get_global_disabled_blocks( WP_REST_Request $request ) {
		$settings = get_option( 'block_filterx_global_disabled_blocks', array() );

		return rest_ensure_response( $settings );
	}

	/**
	 * Update the settings.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error The response or error object.
	 */
	public function update_global_disable_block( $request ) {
		$global_disabled_blocks = get_option( 'block_filterx_global_disabled_blocks', array() );

		// Check if the 'block_name' parameter is provided
		if ( $request->has_param( 'block_name' ) ) {
			$block_name = sanitize_text_field( $request->get_param( 'block_name' ) );

			// Append the block name if it's not already in the array
			if ( ! in_array( $block_name, $global_disabled_blocks, true ) ) {
				$global_disabled_blocks[] = $block_name;
			}
		}

		if ( ! update_option( 'block_filterx_global_disabled_blocks', $global_disabled_blocks ) ) {
			return new WP_Error( 'update_failed', __( 'Failed to update the settings', 'shop-front' ), array( 'status' => 500 ) );
		}
	
		return $this->get_global_disabled_blocks( $request );
	}

	/**
	 * Check if a given request has access to get the settings.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return bool True if the request has access, false otherwise.
	 */
	public function get_settings_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check if a given request has access to update the settings.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return bool True if the request has access, false otherwise.
	 */
	public function update_settings_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get the schema for a single item, if any.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		return array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'settings',
			'type'       => 'object',
			'properties' => array(
				'block_name' => array(
					'description' => __( 'Disable Block Name', 'shop-front' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				)
			),
		);
	}
}
