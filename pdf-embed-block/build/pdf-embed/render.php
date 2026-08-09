<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
    $id = wp_unique_id( 'pebPDFEmbed-' ); 
    $global_options = function_exists( 'pebGetGlobalViewerOptions' ) ? pebGetGlobalViewerOptions() : [];
    $props = [
        'attributes'          => $attributes,
        'pebAPIKey'           => get_option( 'pebAPIKey' ),
        'globalViewerOptions' => $global_options,
    ]; 
?> 

<div 
    <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 
    id="<?php echo esc_attr( $id ); ?>" 
    data-props="<?php echo esc_attr( wp_json_encode( $props ) ); ?>" 
>
</div>