<?php

/*
 * Function to get state of page
 */
    function get_conditional_state($target_post = null){

        $target_post = get_post($target_post);

        // Default to customized state name
        // if( $target_post->_custom_state_name ){
        //     return $target_post->_custom_state_name;
        // }

        // set state conditions here
        switch (true){

            /* Example:
            case $target_post->_custom_guid == 'work-grid'' :
                $output = 'work-grid';
                break;
            case is_tree( 16, $target_post ) and !has_children( $target_post ) :
                $output = 'work-detail';
                break;
            case is_tree( 16, $target_post ) :
                $output = 'campaign-detail';
                break;
            case $target_post->_custom_guid == 'one-of-a-kind' :
                $output = 'one-of-a-kind-post';
                break;
                */

            case $target_post->ID == get_option('page_on_front'):
                $output = 'front-page';
                break;

            case $target_post->ID == get_option('page_for_posts'):
                $output = 'home';
                break;

            default:
                $output = 'fallback';
                break;
        }

        // return post state
        return $output;
    }

?>
