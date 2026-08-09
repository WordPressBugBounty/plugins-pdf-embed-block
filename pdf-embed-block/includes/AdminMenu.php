<?php

namespace PEB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminMenu {
    function __construct() {
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_init', [$this, 'handleLegacyRedirect']);
    }

    function handleLegacyRedirect() {
        if ( isset( $_GET['page'] ) && $_GET['page'] === 'peb_settings_page' ) {
            wp_safe_redirect( admin_url( 'edit.php?post_type=pdf_embed&page=peb_demo_page#/settings' ) );
            exit;
        }
    }

    function adminMenu(){
        add_submenu_page(
            'edit.php?post_type=pdf_embed',
            'Settings',
            'Settings <span style="background: linear-gradient(135deg, #ff6b00, #ff2e00); color: #fff; font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 10px; margin-left: 4px; display: inline-block; line-height: 1.2; text-transform: uppercase; letter-spacing: 0.5px; vertical-align: middle;">NEW</span>', 
            'manage_options',
            'edit.php?post_type=pdf_embed&page=peb_demo_page#/settings',
            [$this, 'renderSettingsPage']
        );


        add_submenu_page(
            'edit.php?post_type=pdf_embed',
            'Help & Demos',
            '<span style="color: #f18500; font-weight: 600;">Help & Demos</span>', 
            'manage_options',
            'peb_demo_page',
            [$this, 'renderDemoPage']
        );
    }

    function renderSettingsPage(){
        wp_safe_redirect( admin_url( 'edit.php?post_type=pdf_embed&page=peb_demo_page#/settings' ) );
        exit;
    }

    function renderDemoPage(){
        ?>
            <div
                id='pebCurrentBplDashboard'
                data-info='<?php echo esc_attr( wp_json_encode( [
                    'version' => PEB_PLUGIN_VERSION,
                    'isPremium' => false,
                    'hasPro' => false,
                    'adminUrl' => admin_url(),
                    'deleteDataOnUninstall' => (bool) get_option( 'peb_delete_data_on_uninstall', false ),
                    'uninstallNonce'        => wp_create_nonce( 'peb_uninstall_nonce' ),
                    'globalViewerOptions'   => function_exists( 'pebGetGlobalViewerOptions' ) ? pebGetGlobalViewerOptions() : [],
                    'globalOptionsNonce'    => wp_create_nonce( 'peb_global_options_nonce' ),
                ] ) ); ?>'
            ></div>
        <?php
    }
}
