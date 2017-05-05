<?php

/*
 * Function to get state of page
 */
    function get_conditional_state($target_post = null){
        $target_post = get_post($target_post);

        // make key that's unique to this post
        $transient_key = 'fh_state_' . $target_post->ID;

        // check for transient, set a new one if needed.
        if ( ! $output = get_transient( $transient_key ) ){

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

            // set new 1 second transient
            set_transient( $transient_key, $output, 1 );

        }

        // return post state
        return $output;
    }

?>
