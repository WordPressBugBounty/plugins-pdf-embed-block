<?php

namespace PEB;

class Enqueue {
    function __construct() {
        add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'script_loader_tag', [$this, 'scriptLoaderTag'], 10, 3 );
        add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);
    }

    function enqueueBlockAssets(){
        // adobe viewer
		wp_register_script( 
            'adobe-viewer', 
            'https://documentcloud.adobe.com/view-sdk/viewer.js', 
            [], 
            PEB_PLUGIN_VERSION 
        );

         if ( function_exists('peb_fs') && peb_fs()->can_use_premium_code() ) {

                wp_register_script(
                    'dflip-script',
                    PEB_DIR_URL . 'public/dflip/js/dflip.min.js',
                    ['jquery'],
                    PEB_PLUGIN_VERSION,
                    true
                );

                wp_register_style(
                    'dflip-style',
                    PEB_DIR_URL . 'public/dflip/css/dflip.min.css',
                    [],
                    PEB_PLUGIN_VERSION
                );
        }

         wp_enqueue_script(
            'peb-script',
            PEB_DIR_URL . 'build/blocks/mozila-viewer/index.js',
            ['wp-element'],
            PEB_PLUGIN_VERSION,
            true
        );  
        
         wp_localize_script(
            'peb-script',
            'BPLG_DATA',
            [
                'pdfjs_url' => PEB_DIR_URL . 'public/pdfjs/web/viewer.html'
            ]
        );
	}

	function scriptLoaderTag( $tag, $handle, $src ){
		if($handle === 'adobe-viewer'){
			return "<script src='https://documentcloud.adobe.com/view-sdk/viewer.js'></script>";
		}
		return $tag;
	}

    function adminEnqueueScripts($screen){
        global $typenow;
        
        if ('pdf_embed' === $typenow) {

            wp_enqueue_script( 'admin-post-js', PEB_DIR_URL . 'build/admin-post.js', [], PEB_PLUGIN_VERSION, true );
            wp_enqueue_style( 'admin-post-css', PEB_DIR_URL . 'build/admin-post.css', [], PEB_PLUGIN_VERSION );

            if ($screen === "pdf_embed_page_peb_demo_page") {
                wp_enqueue_script( 'bpl-admin-dashboard-js', PEB_DIR_URL . 'build/admin-dashboard.js', [ 'react', 'react-dom', 'wp-util' ], PEB_PLUGIN_VERSION, true );
                wp_enqueue_style( 'bpl-admin-dashboard-css', PEB_DIR_URL . 'build/admin-dashboard.css', [], PEB_PLUGIN_VERSION );
            }

        }
    }
  
}