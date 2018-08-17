<?php
/*
 * This file handles the custom Gutenberg blocks for this theme
 */

    function register_vuepress_blocks(){
        if( !function_exists('register_block_type') ) return;

        wp_register_script(
           'gutenberg-boilerplate-es5-step01',
           get_template_directory_uri() . '/blocks/block.js',
           array( 'wp-blocks', 'wp-element' )
       );

       register_block_type( 'gutenberg-boilerplate-es5/hello-world-step-01',
       array(
           'editor_script' => 'gutenberg-boilerplate-es5-step01',
       ) );

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
