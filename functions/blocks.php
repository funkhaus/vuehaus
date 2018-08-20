<?php
/*
 * This file handles the custom Gutenberg blocks for this theme
 */
    function register_vuepress_blocks(){
        // Ignore if Gutenberg isn't installed
        if( !function_exists('register_block_type') ) return;

        // Register custom blocks
        wp_register_script(
           'vuepress-block-registration',
           get_template_directory_uri() . '/blocks/dist/blocks.js',
           array( 'wp-blocks', 'wp-element' )
       );

       register_block_type( 'vuepress-blocks/vuepress-blocks',
       array(
           'editor_script' => 'vuepress-block-registration',
       ) );

       // Add this theme to the custom block categories
       add_filter( 'block_categories', function( $categories ) {
        	return array_merge(
        		$categories,
        		array(
        			array(
        				'slug' => 'custom-fh',
        				'title' => __( wp_get_theme()['Name'] ),
        			),
        		)
        	);
        }, 10, 2 );
    }
    add_action('init', 'register_vuepress_blocks');
