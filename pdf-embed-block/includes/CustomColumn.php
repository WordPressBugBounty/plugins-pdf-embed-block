<?php

namespace PEB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CustomColumn {
    function __construct() {
        add_filter('manage_pdf_embed_posts_columns', [$this, 'ManageColumns'], 10);
        add_action('manage_pdf_embed_posts_custom_column', [$this, 'ManageCustomColumns'], 10, 2);  
    }

    function ManageColumns($defaults){
        unset($defaults['date']);
        $defaults['shortcode'] = 'ShortCode';
        $defaults['date'] = 'Date';
        return $defaults;
    }

    function ManageCustomColumns($column_name, $post_ID){
        if ($column_name == 'shortcode') {
            echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr($post_ID) . '">
                    <input value="[pdf_embed id=' . esc_attr($post_ID) . ']" onclick="copyBPlAdminShortcode(\'' . esc_attr($post_ID) . '\')" readonly>
                    <span class="tooltip">Copy To Clipboard</span>
                  </div>';
        }
    }

}