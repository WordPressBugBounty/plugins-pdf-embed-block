<?php
/**
 * Plugin Name: PDF Embed Block – Embed PDF Files in Posts or Pages
 * Plugin URI: https://bplugins.com/products/pdf-embed-block
 * Description: Embed PDF files easily in your pages and posts.
 * Version: 1.3.2
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: pdf-embed-block
 * Tested up to: 7.0
 * Requires at least: 6.5
 * Requires PHP: 7.1
 */ 
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'peb_fs' ) ) {
    peb_fs()->set_basename( true, __FILE__ );
} else {
    define( 'PEB_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.3.2' );
    define( 'PEB_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'PEB_DIR_PATH', plugin_dir_path( __FILE__ ) );

    if ( ! function_exists( 'peb_fs' ) ) {
        function peb_fs() {
            global $peb_fs;
    
            if ( ! isset( $peb_fs ) ) {

				require_once PEB_DIR_PATH . '/vendor/freemius-lite/start.php';

                $peb_fs = fs_lite_dynamic_init( [ 
                    'id'                  => '21138',
                    'slug'                => 'pdf-embed-block',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_aaf93da06d368386d3fd060373257',
                    'is_premium'          => false,
                    'menu'                => array(
                        'slug'            => 'edit.php?post_type=pdf_embed',
                        'first-path'      => 'edit.php?post_type=pdf_embed&page=peb_demo_page#/welcome',
                        'support'         => false,
                    ),
				] );

            }
    
            return $peb_fs;
        }   
    
        peb_fs();

        do_action( 'peb_fs_loaded' );
    }
    
    require_once PEB_DIR_PATH . 'includes/core.php';

    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), function( $links ) {
		$help_link = '<a href="' . admin_url( 'edit.php?post_type=pdf_embed&page=peb_demo_page' ) . '" style="color:#f18500;font-weight:bold;">Help & Demos</a>';

		$links[] = $help_link;

		return $links;
	} );
    
}



