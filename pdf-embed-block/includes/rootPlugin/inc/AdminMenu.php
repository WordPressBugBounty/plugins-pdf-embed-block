<?php

namespace PEB;

class AdminMenu  {
    function __construct() {
        add_action('admin_menu', [$this, 'adminMenu']);
    }

    function adminMenu(){
        add_submenu_page(
            'edit.php?post_type=pdf_embed',
            'Help & Demos',
            '<span style="color: #f18500; font-weight: 600;">Help & Demos</span>', 
            'manage_options',
            'peb_demo_page',
            [$this, 'renderDemoPage']
        );
    }

    function renderDemoPage(){
        ?>
            <div
                id='pebCurrentBplDashboard'
                data-info='<?php echo esc_attr( wp_json_encode( [
                    'version' => PEB_PLUGIN_VERSION,
                    'isPremium' => pebIsPremium(),
                    'hasPro' => PEB_HAS_FRMS,
                    'licenseActiveNonce' => wp_create_nonce( 'bPlLicenseActivation' ),
                    'action' => 'pebGetBlocks',
                    'nonce' => wp_create_nonce( 'peb_admin_nonce' ),
                ] ) ); ?>'
            ></div>
        <?php
    }
}