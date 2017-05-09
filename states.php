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

        // check for transient, set a new one if needed.
        if ( ! $output = get_post_meta( $target_post->ID, '_custom_state', true ) ){

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

                default:
                    $output = 'front-page';
                    break;
            }

            // update state meta
            update_post_meta( $target_post, '_custom_state', $output );

        }

        // return post state
        return $output;
    }

?>
