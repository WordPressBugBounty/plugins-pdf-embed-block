<?php

namespace PEB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RestAPI {
    public function __construct() {
		add_action( 'wp_ajax_pebSaveUninstallOption', [ $this, 'peb_save_uninstall_option' ] );
		add_action( 'wp_ajax_pebSaveGlobalViewerOptions', [ $this, 'peb_save_global_viewer_options' ] );
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

	public function peb_save_global_viewer_options() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'peb_global_options_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid security token.', 'pdf-embed-block' ) ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to manage options.', 'pdf-embed-block' ) ) );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- $_POST['options'] is a JSON string, sanitized after json_decode.
		$options_raw = isset( $_POST['options'] ) ? wp_unslash( $_POST['options'] ) : '';
		if ( is_string( $options_raw ) ) {
			$options_raw = json_decode( $options_raw, true );
		}

		$sanitized = array(
			'showDownloadPDF' => isset( $options_raw['showDownloadPDF'] ) ? filter_var( $options_raw['showDownloadPDF'], FILTER_VALIDATE_BOOLEAN ) : false,
			'showPrintPDF'    => isset( $options_raw['showPrintPDF'] ) ? filter_var( $options_raw['showPrintPDF'], FILTER_VALIDATE_BOOLEAN ) : false,
			'showFullScreen'  => isset( $options_raw['showFullScreen'] ) ? filter_var( $options_raw['showFullScreen'], FILTER_VALIDATE_BOOLEAN ) : true,
			'forceGlobal'     => isset( $options_raw['forceGlobal'] ) ? filter_var( $options_raw['forceGlobal'], FILTER_VALIDATE_BOOLEAN ) : false,
		);

		update_option( 'peb_global_viewer_options', $sanitized );

		wp_send_json_success( array(
			'options' => $sanitized,
			'message' => __( 'Global PDF viewer settings profile saved successfully.', 'pdf-embed-block' )
		) );
	}

}