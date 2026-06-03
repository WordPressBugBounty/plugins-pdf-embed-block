<?php
/**
 * PDF Embed Block – Embed PDF Files in Posts or Pages Uninstall
 *
 * This file is called when the plugin is deleted from the WordPress admin dashboard.
 * It cleans up all the data stored by the plugin in the database.
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Only delete data if user has enabled this setting
if ( get_option( 'peb_delete_data_on_uninstall', false ) ) {
	// Delete all pdf_embed custom posts
	$pdf_embed_posts = get_posts( array(
		'post_type'   => 'pdf_embed',
		'numberposts' => -1,
		'post_status' => 'any',
	) );

	if ( ! empty( $pdf_embed_posts ) ) {
		foreach ( $pdf_embed_posts as $post ) {
			wp_delete_post( $post->ID, true ); // Force delete (bypasses trash)
		}
	}

	// Delete options registered by the plugin
	delete_option( 'pebAPIKey' );

	// Delete the uninstall option itself
	delete_option( 'peb_delete_data_on_uninstall' );
}
