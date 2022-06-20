<?php
/**
 * Plugin Name:       Entire Blocks
 * Description:       Entire - WordPress Gutenberg Blocks plugin for creating amazing WordPress page with grid layout latest posts. It has color options to make it professional.
 * Version:           1.0.0
 * Author:            Birendra Maharjan
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       entire-blocks
 *
 * @package           entire-blocks
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

require_once plugin_dir_path( __FILE__ ) . 'inc/entire-generate-post-html.php';

if ( ! class_exists( 'ENTIREBLOCKS' ) ) :

	class ENTIREBLOCKS {
		function __construct() {
			add_action( 'init', array( $this, 'entire_init' ) );

			//redirect single
			add_filter( 'redirect_canonical', array( $this, 'entire_disable_redirect_canonical' ) );

			add_action( 'rest_api_init', array( $this, 'entire_get_post_data' ) );
		}

		function entire_init() {
			register_block_type( __DIR__ . '/build', array(
				'render_callback' => array( $this, 'entire_the_render_callBack' )
			) );
		}

		function entire_the_render_callBack( $attributes ) {
			if ( $attributes['showBlog'] ) {
				return entire_generate_post_html( $attributes );
			} else {
				return null;
			}
		}

		/**
		 * Redirects incoming links to the proper URL based on the site url.
		 * for single page pagination
		 */
		function entire_disable_redirect_canonical( $redirect_url ) {
			if ( is_single() ) {
				$redirect_url = false;
			}

			return $redirect_url;
		}

		/**
		 * Register rest for get post data
		 */
		function entire_get_post_data() {
			register_rest_route( 'getPosts/v1', 'getHTML', array(
				'methods'             => WP_REST_SERVER::READABLE,
				'callback'            => array( $this, 'entire_get_post_html' ),
				'permission_callback' => '__return_true'
			) );
		}

		function entire_get_post_html( $attributes = null ) {

			if ( $attributes['showBlog'] ) {
				return entire_generate_post_html( $attributes );
			} else {
				return null;
			}
		}

		function load_ajax_archive_list() {
			wp_send_json_error( array( 'status' => true, 'data' => $this->entire_get_post_html() ) );
		}

	}

	// Instantiate.
	$entireblocks = new ENTIREBLOCKS();

endif; // class_exists check
