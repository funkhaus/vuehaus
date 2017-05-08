<?php

    include_once( dirname(__FILE__) . '/serializers.php' );

    function build_meta( $post = null, $state = 'default' ){

        $meta = array(
            'self'      => get_permalink($post),
            'state'     => $state
        );

        return $meta;
    }

    function build_shared( $post = null, $state = 'default' ){

        $shared = array(
            // TODO: build menu item serializer - active, permalink
            // TODO: Note when query is 404
            'mainMenu'     => wp_get_nav_menu_items('Main Menu')
        );

        return $shared;

    }

    function load_query_data ($post = null) {

        $state = get_conditional_state( $post );

        // Serializers define $data
        $path = dirname(__FILE__) . '/' . $state . '.php';

        // Build $data
        if ( !realpath($path) ){
            $path = dirname(__FILE__) . '/default.php';
        }
        include $path;

        $export = array(
            'meta'      => build_meta( $post, $state ),
            'shared'    => build_shared( $post, $state ),
            'data'      => $data,
            'state'     => $state
        );

        return $export;
    }
