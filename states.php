<?php

    function update_conditional_state( $post_id ){
        delete_post_meta( $post_id, '_custom_state' );
        get_conditional_state( $post_id );
    }
    add_action( 'save_post', 'update_conditional_state' );

/*
 * Function to get state of page
 */
    function get_conditional_state($target_post = null){

        $target_post = get_post($target_post);

        // set state conditions here
        switch (true){

            /* Example:
            case $target_post->ID == 16 :
                $output = 'work-grid';
                break;
            case is_tree( 16, $target_post ) :
                $output = 'work-detail';
                break;
                */

            case $target_post->ID == get_option('page_on_front'):
                $output = 'front-page';
                break;

            default:
                $output = 'fallback';
                break;
        }

        // return post state
        return $output;
    }

?>
