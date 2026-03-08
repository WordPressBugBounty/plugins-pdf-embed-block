<?php

namespace PEB;

class RestAPI {
    function __construct() {
        add_action('wp_ajax_pebPremiumChecker', [$this, 'pebPremiumChecker']);
        add_action('wp_ajax_nopriv_pebPremiumChecker', [$this, 'pebPremiumChecker']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('rest_api_init', [$this, 'registerSettings']);    
        add_action('wp_ajax_pebGetBlocks', [ $this, 'pebGetBlocks_callback' ]);
    }

    function pebPremiumChecker(){
        $nonce = sanitize_text_field($_POST['_wpnonce'] ?? null);

        if (!wp_verify_nonce($nonce, 'wp_ajax')) {
            wp_send_json_error('Invalid Request');
        }

        wp_send_json_success([
            'isPipe' => pebIsPremium()
        ]);
    }

    function registerSettings(){
        register_setting('pebUtils', 'pebUtils', [
            'show_in_rest' => [
                'name' => 'pebUtils',
                'schema' => ['type' => 'string']
            ],
            'type' => 'string',
            'default' => wp_json_encode(['nonce' => wp_create_nonce('wp_ajax')]),
            'sanitize_callback' => 'sanitize_text_field'
        ]);
    }

    public function pebGetBlocks_callback(){
        $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ?? null;

        if( !wp_verify_nonce( $nonce, 'peb_admin_nonce' )){
            wp_send_json_error( 'Invalid Request' );
        }

        $data = json_decode( stripslashes( $_POST['data'] ), true );
        $db_data = get_option( 'pebBlocks', [] );

        if( !isset( $data ) && $db_data ){
            wp_send_json_success( $db_data );
        }

        update_option( 'pebBlocks', $data );
        wp_send_json_success( $data );

    }

}