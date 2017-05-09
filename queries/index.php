<?php

    include_once( dirname(__FILE__) . '/serializers.php' );

    function build_meta( $post = null, $state = 'default' ){

        $meta = array(
            'self'      => get_permalink($post),
            'state'     => $state
        );

        return $meta;
    }

    function build_routes(){

        // Unchanging
        $category_base = '/' . get_option('category_base');

        return array(

            // Per-site
            // '/path'                              => 'VueComponent',
            // '/path/:var'                         => 'ComponentWithVar'
            // '/path/*/:var'                       => 'WildcardAndVar'

            // Probably unchanging
            ''                                      => 'FrontPage',
            '/' . $category_base                    => 'archive',
            '/:slug'                                => 'default'
        );

    }

    function build_shared( $post = null, $state = 'default' ){

        $shared = array(
            'mainMenu'      => serializer_menu( 'Main Menu' ),
            'routes'        => build_routes(),
            'themeUrl'      => get_template_directory_uri(),
            'is404'         => is_404()
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
