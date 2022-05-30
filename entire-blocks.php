<?php
/**
 * Plugin Name:       Entire - WordPress Gutenberg Blocks
 * Description:       Entire - WordPress Gutenberg Blocks plugin for creating fully customized News, Magazines, Websites, Personal Blogs, Travel Blogs, Fashion Blogs, Viral News, Food Reviews, Recipes Blogs, etc.
 * Version:           0.1.0
 * Author:            Birendra Maharjan
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       entire-blocks
 *
 * @package           entire-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once plugin_dir_path(__FILE__) . 'inc/generate-post-html.php';

if (!class_exists('AGUTENBLOG')) :

    class AGUTENBLOG
    {
        function __construct()
        {
            add_action('init', array($this, 'on_init'));

            add_action('wp_enqueue_scripts', array($this, 'entire_blocks_enqueue'));

            //redirect single
            add_filter('redirect_canonical', array($this, 'disable_redirect_canonical'));

            add_action('rest_api_init', array($this, 'post_html'));

            add_action("wp_ajax_load_ajax_archive_list", array($this, "load_ajax_archive_list"));
            add_action("wp_ajax_nopriv_load_ajax_archive_list", array($this,"load_ajax_archive_list"));

        }

        function on_init()
        {
            register_block_type(__DIR__ . '/build', array(
                'render_callback' => array($this, 'the_render_callBack')
            ));
        }

        /**
         * Enqueue scripts and styles of your Gutenberg blocks

         */
        public function entire_blocks_enqueue()
        {
            $dir = dirname( __FILE__ );

            $script_asset_path = "$dir/build/main.asset.php";
            $script_asset = require( $script_asset_path );

           // wp_enqueue_script( 'entire-blocks-script', plugin_dir_url( __FILE__ ) . 'build/main.js', array('jquery'), '', true );

            wp_register_script( 'entire-blocks-script', plugin_dir_url( __FILE__ ) . 'build/main.js', $script_asset['dependencies'], $script_asset['version'], true );
            wp_localize_script( 'entire-blocks-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
            wp_enqueue_script( 'entire-blocks-script' );

        }

        function the_render_callBack($attributes)
        {
            if ($attributes['showBlog']) {
                return generate_post_html($attributes);
            } else {
                return NULL;
            }
        }

        //redirect single
        function disable_redirect_canonical($redirect_url)
        {
            if (is_single()) $redirect_url = false;
            return $redirect_url;
        }

        //http://localhost/wordpress/gutenberg/wordpress/wp-json/getPosts/v1/getHTML
        function post_html()
        {
            register_rest_route('getPosts/v1', 'getHTML', array(
                'methods' => WP_REST_SERVER::READABLE,
                'callback' => array($this, 'get_post_html'),
                'permission_callback' => '__return_true'
            ));
        }

        function get_post_html($attributes = null)
        {
            if(isset($attributes['id']) && !empty($attributes['id'])):
               return array('status' => true, 'posts' => generate_post_html1($attributes));
            else:
                return generate_post_html($attributes);
            endif;
            //if ($attributes['showBlog']) {
              //  return generate_post_html($attributes);
           /* } else {
                return NULL;
            }*/
        }

        function load_ajax_archive_list()
        {
            wp_send_json_error(array('status' => true, 'data' => $this->get_post_html()));
        }

    }

    // Instantiate.
    $agutenblog = new AGUTENBLOG();

endif; // class_exists check
?>