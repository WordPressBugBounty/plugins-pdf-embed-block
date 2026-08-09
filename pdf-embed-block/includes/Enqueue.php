<?php

namespace PEB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Enqueue {
    function __construct() {
        add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
        add_action( 'script_loader_tag', [$this, 'scriptLoaderTag'], 10, 3 );
        add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);
        add_action( 'wp_head', [$this, 'injectBplgData'] );
        add_action( 'admin_head', [$this, 'injectBplgData'] );
    }


    function enqueueBlockAssets(){
        // adobe viewer
		wp_register_script( 
            'adobe-viewer', 
            'https://documentcloud.adobe.com/view-sdk/viewer.js', 
            [], 
            PEB_PLUGIN_VERSION,
            false 
        );

        wp_localize_script(
            'wp-blocks',
            'PEB_BLOCK_DATA',
            [
                'disabledBlocks'      => [],
                'isPremium'           => false,
                'globalViewerOptions' => function_exists('pebGetGlobalViewerOptions') ? pebGetGlobalViewerOptions() : [],
            ]
        );
	}

	function injectBplgData() {
		$data = [
			'pdfjs_url'           => PEB_DIR_URL . 'public/pdfjs/web/viewer.html',
			'globalViewerOptions' => function_exists('pebGetGlobalViewerOptions') ? pebGetGlobalViewerOptions() : [],
		];
		echo '<script>window.BPLG_DATA = ' . wp_json_encode( $data ) . ';</script>' . "\n";
	}


	function scriptLoaderTag( $tag, $handle, $src ){
		if($handle === 'adobe-viewer'){
			return "<script src='https://documentcloud.adobe.com/view-sdk/viewer.js'></script>"; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		}
		return $tag;
	}

    function adminEnqueueScripts($screen){
        global $typenow;
        
        if ('pdf_embed' === $typenow) {

            wp_enqueue_script( 'admin-post-js', PEB_DIR_URL . 'build/admin-post.js', [], PEB_PLUGIN_VERSION, true );
            wp_enqueue_style( 'admin-post-css', PEB_DIR_URL . 'build/admin-post.css', [], PEB_PLUGIN_VERSION );

            if ($screen === "pdf_embed_page_peb_demo_page" || $screen === "pdf_embed_page_peb_settings_page") {
                wp_enqueue_script( 'bpl-admin-dashboard-js', PEB_DIR_URL . 'build/admin-dashboard.js', [ 'react', 'react-dom', 'wp-util' ], PEB_PLUGIN_VERSION, true );
                wp_enqueue_style( 'bpl-admin-dashboard-css', PEB_DIR_URL . 'build/admin-dashboard.css', [], PEB_PLUGIN_VERSION );
            }


        }
    }
  
}