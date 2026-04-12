<?php

namespace PEB;

class Init {
    function __construct() {
        add_action( 'init', [ $this, 'onInit' ] );    
    }

    function onInit() {

		register_setting(
			'pebPluginSettings',
			'pebAPIKey',
			[
				'default'		=> '',
				'show_in_rest'	=> true,
				'type'			=> 'string'
			]
		);
		
		$this->peb_register_blocks();

		$template = [];

		if ( peb_fs()->can_use_premium_code() ) {
			$template = [
				["pebp/document-viewer-selector"]
			];
		} else {

			$template = [
				["peb/pdf-embed"]
			];
		}

		register_post_type('pdf_embed', [
			'label' => __('PDF Embeds', 'pdf-embed-block'),
			'labels' => [
				'name'                  => __('PDF Embeds', 'pdf-embed-block'),
				'singular_name'         => __('PDF Embed', 'pdf-embed-block'),
				'add_new'               => __('Add New', 'pdf-embed-block'),
				'add_new_item'          => __('Add New PDF Embed', 'pdf-embed-block'),
				'edit_item'             => __('Edit PDF Embed', 'pdf-embed-block'),
				'new_item'              => __('New PDF Embed', 'pdf-embed-block'),
				'view_item'             => __('View PDF Embed', 'pdf-embed-block'),
				'view_items'            => __('View PDF Embeds', 'pdf-embed-block'),
				'search_items'          => __('Search PDF Embeds', 'pdf-embed-block'),
				'not_found'             => __('No PDF Embeds found.', 'pdf-embed-block'),
				'not_found_in_trash'    => __('No PDF Embeds found in Trash.', 'pdf-embed-block'),
				'all_items'             => __('All PDF Embeds', 'pdf-embed-block'),
				'archives'              => __('PDF Embed Archives', 'pdf-embed-block'),
			],
            'show_in_rest' => true,
            'public' => true,
            'publicly_queryable' => false,
            'item_published' => 'PDF Embed Block Published',
            'item_updated' => 'PDF Embed Block Updated',
			'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
					<rect width="512" height="512" rx="15%" fill="var(--wp-admin-theme-color)" />
					<path  d="M413 302c-9-10-29-15-56-15-16 0-33 2-53 5a252 252 0 0 1-52-69c10-30 17-59 17-81 0-17-6-44-30-44-7 0-13 4-17 10-10 18-6 58 13 100a898 898 0 0 1-50 117c-53 22-88 46-91 65-2 9 4 24 25 24 31 0 65-45 91-91a626 626 0 0 1 92-24c38 33 71 38 87 38 32 0 35-23 24-35z" />
				</svg>
			'),
			'template'            => $template,
			'template_lock'       => 'all',
        ]);

		wp_set_script_translations( 'peb-pdf-embed-editor-script', 'pdf-embed-block', PEB_DIR_PATH . 'languages' );
	}

	function peb_register_blocks() {
		$blocks_path = PEB_DIR_PATH . '/build/blocks/';
		$all_blocks  = glob( $blocks_path . '*', GLOB_ONLYDIR );

		if ( empty( $all_blocks ) ) {
			return;
		}

		$disabled_blocks = get_option( 'pebBlocks', [] );

		if ( ! is_array( $disabled_blocks ) ) {
			$disabled_blocks = [];
		}

		$is_premium = peb_fs()->can_use_premium_code();

		wp_register_script( 'peb-common-data', PEB_DIR_URL . 'build/peb-common-data.js', [], PEB_PLUGIN_VERSION, true );

		// Register shared assets for pro blocks
		if ( $is_premium ) {
			$asset_path = PEB_DIR_PATH . 'build/blocks/index.asset.php';
			$asset_file = file_exists( $asset_path )
				? include $asset_path
				: [
					'dependencies' => [ 'wp-blocks', 'wp-element', 'wp-i18n' ],
					'version'      => PEB_PLUGIN_VERSION,
				];

			wp_register_script(
				'peb-pro-blocks',
				PEB_DIR_URL . 'build/blocks/index.js',
				$asset_file['dependencies'],
				$asset_file['version'],
				true
			);

			wp_register_style(
				'peb-pro-blocks',
				PEB_DIR_URL . 'build/blocks/index.css',
				[],
				$asset_file['version']
			);
		}


		foreach ( $all_blocks as $block_path ) {
			$block_name = basename( $block_path );

			if ( in_array( $block_name, $disabled_blocks, true ) ) {
				continue;
			}

			if ( $block_name === 'pdf-embed' || $block_name === 'parent' ) {
				register_block_type( $block_path );
				continue;
			}

			if ( $is_premium ) {
				register_block_type( $block_path, [
					'editor_script' => 'peb-pro-blocks',
					'editor_style'  => 'peb-pro-blocks',
				] );
			}
		}
	}

}