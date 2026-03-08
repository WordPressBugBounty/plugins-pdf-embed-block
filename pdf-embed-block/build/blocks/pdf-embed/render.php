<?php 
    $id = wp_unique_id( 'pebPDFEmbed-' ); 
    $props = [ 'attributes' => $attributes, 'pebAPIKey' => get_option( 'pebAPIKey' )]; 
?> 

<div 
    <?php echo get_block_wrapper_attributes(); ?> 
    id="<?php echo esc_attr( $id ); ?>" 
    data-props="<?php echo esc_attr( wp_json_encode( $props ) ); ?>" 
>
</div>