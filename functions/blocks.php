<?php
/*
 * This file handles the custom Gutenberg blocks for this theme
 */

    function register_vuepress_blocks(){
        if( !function_exists('register_block_type') ) return;

        echo 'xyz';
    }
    add_action('init', 'register_vuepress_blocks');
