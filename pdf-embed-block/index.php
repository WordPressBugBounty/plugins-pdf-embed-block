<?php

/**
 * Plugin Name: PDF Embed Block
 * Description: Embed PDF files easily in your pages and posts.
 * Version: 1.2.5
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: pdf-embed-block
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'peb_fs' ) ) {
    peb_fs()->set_basename( false, __FILE__ );
} else {
    define( 'PEB_PLUGIN_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.2.5' ) );
    define( 'PEB_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'PEB_DIR_PATH', plugin_dir_path( __FILE__ ) );
    define( 'PEB_HAS_FRMS', file_exists( dirname( __FILE__ ) . '/vendor/freemius/start.php' ) );
    if ( !function_exists( 'peb_fs' ) ) {
        function peb_fs() {
            global $peb_fs;
            if ( !isset( $peb_fs ) ) {
                if ( PEB_HAS_FRMS ) {
                    require_once PEB_DIR_PATH . '/vendor/freemius/start.php';
                } else {
                    require_once PEB_DIR_PATH . '/vendor/freemius-lite/start.php';
                }
                $pebConfig = [
                    'id'                  => '21138',
                    'slug'                => 'pdf-embed-block',
                    'premium_slug'        => 'pdf-embed-block-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_aaf93da06d368386d3fd060373257',
                    'is_premium'          => PEB_HAS_FRMS,
                    'premium_suffix'      => 'Pro',
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'menu'                => array(
                        'slug'       => 'edit.php?post_type=pdf_embed',
                        'first-path' => 'edit.php?post_type=pdf_embed&page=peb_demo_page#/welcome',
                        'support'    => false,
                    ),
                ];
                $peb_fs = ( PEB_HAS_FRMS ? fs_dynamic_init( $pebConfig ) : fs_lite_dynamic_init( $pebConfig ) );
            }
            return $peb_fs;
        }

        peb_fs();
        do_action( 'peb_fs_loaded' );
    }
    if ( PEB_HAS_FRMS ) {
        require_once PEB_DIR_PATH . 'includes/LicenseActivation.php';
    }
    require_once PEB_DIR_PATH . 'includes/utility/functions.php';
    require_once PEB_DIR_PATH . 'includes/rootPlugin/plugin.php';
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
        $help_link = '<a href="' . admin_url( 'edit.php?post_type=pdf_embed&page=peb_demo_page' ) . '" style="color:#f18500;font-weight:bold;">Help & Demos</a>';
        $links[] = $help_link;
        return $links;
    } );
}