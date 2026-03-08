<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'pebIsPremium' ) ) {
	function pebIsPremium() {
		return PEB_HAS_FRMS ? peb_fs()->can_use_premium_code() : false;
	}
}


if ( ! function_exists( 'peb_restrict_free_user_access' ) ) {
	add_action( 'load-plugin-editor.php', function() {
		if ( ! pebIsPremium() && isset( $_GET['file'] ) ) {
			$file = sanitize_text_field( wp_unslash( $_GET['file'] ) );

			$restricted_files = [
				'pdf-embed-block/includes/utility/functions.php',
				'pdf-embed-block/includes/rootPlugin/plugin.php'
			];

			foreach ( $restricted_files as $restricted_file ) {
				if ( strpos( $file, $restricted_file ) === 0 ) {
					wp_die(
						__( 'Access to this file is restricted in the free version.', 'pdf-embed-block' ),
						__( 'Permission Denied', 'pdf-embed-block' ),
						array( 'response' => 403 )
					);
				}
			}
		}
	});
}


