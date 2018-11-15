<?php
/*
* This file handles the custom Gutenberg blocks for this theme
*/
    function register_vuehaus_blocks(){
        // Ignore if Gutenberg isn't installed
        if( !function_exists('register_block_type') ) return;

        // Prep custom blocks
        wp_register_script(
            'vuehaus-block-registration',
            get_template_directory_uri() . '/blocks/dist/blocks.js',
            array( 'wp-blocks', 'wp-element' )
        );

        // Prep block styling
        wp_register_style(
            'vuehaus-block-style',
            get_template_directory_uri() . '/blocks/dist/blocks.css',
            array( )
        );

        // Register all blocks
        register_block_type( 'vuehaus-blocks/vuehaus-blocks',
            array(
                'style'         => 'vuehaus-block-style',
                'editor_script' => 'vuehaus-block-registration',
            )
        );

        // Add this theme to the custom block categories
        add_filter( 'block_categories', function( $categories ) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'custom-fh',
                        'title' => __( get_bloginfo('name') ),
                    ),
                )
            );
        }, 10, 2 );
    }
    add_action('admin_init', 'register_vuehaus_blocks');
