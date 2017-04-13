<?php

    function pull_serializer($slug){
        $export = array();
        include $slug . '.php';
        return $export;
    }

    function load_query_data () {
        global $post;
        $state = get_conditional_state($post);

        switch (true){
            case is_front_page():
                return pull_serializer('home');
                break;
            default:
                $fallback = file_exists( $state . '.php' ) ? $state : 'default';
                return pull_serializer( $fallback );
                break;
        }
    }
