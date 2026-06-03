<?php

namespace PEB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RestAPI {
    public function __construct() {
		add_action( 'wp_ajax_pebSaveUninstallOption', [ $this, 'peb_save_uninstall_option' ] );
	}

	public function peb_save_uninstall_option() {
		// Check nonce.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'peb_uninstall_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid security token.', 'pdf-embed-block' ) ) );
		}

		// Check user capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to manage options.', 'pdf-embed-block' ) ) );
		}

		// Check enabled post value.
		$enabled = isset( $_POST['enabled'] ) && filter_var( wp_unslash( $_POST['enabled'] ), FILTER_VALIDATE_BOOLEAN );

		// Update option.
		update_option( 'peb_delete_data_on_uninstall', $enabled );

		wp_send_json_success( array(
			'enabled' => $enabled,
			'message' => $enabled 
				? __( 'Data will be deleted on uninstall.', 'pdf-embed-block' ) 
				: __( 'Data will be preserved on uninstall.', 'pdf-embed-block' )
		) );
	}

   

}