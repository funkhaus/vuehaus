<?php

    function serializer_image( $attachment = null ){

        $output = array();

        $attachment = get_post($attachment);
        $sizes = get_intermediate_image_sizes();

        foreach ( $sizes as $size ){
            $img_data = wp_get_attachment_image_src($attachment->ID, $size);

            $output[$size] = array(
                'url'       => $img_data[0],
                'width'     => $img_data[1],
                'height'    => $img_data[2]
            );
        }

        return $output;
    }

    // Define all serializers here

    function serializer_standard( $target_post = null ){

        $target_post = get_post( $target_post );

        if( ! $target_post ){
            return '';
        }

        // Get all meta fields without leading underscore
        $meta = get_post_meta( $target_post->ID, '', true );
        $filtered_meta = array_filter( $meta, 'filter_leading_underscore', ARRAY_FILTER_USE_KEY );

        $output = array(
            'title'         => get_the_title($target_post),
            'content'       => apply_filters('the_content', $target_post->post_content),
            'excerpt'       => get_the_excerpt($target_post),
            'permalink'     => get_permalink($target_post),
            'slug'          => $target_post->post_name,
            'relativeLink'  => remove_siteurl( $target_post ),
            'featuredImage' => serializer_image( get_post_thumbnail_id( $target_post->ID ) ),
            'meta'          => array_map( 'reset', $filtered_meta )
        );

        return $output;
    }

    // Used on array_filter to remove $key with leading underscore ( _fails )
    function filter_leading_underscore( $key ){
        return ! preg_match('/^_/', $key);
    }

    // Removes site url to retrieve relative path
    function remove_siteurl( $target_post ){
        $replaced = preg_replace(
            '@' . get_option('siteurl') . '@',
            '',
            (string)get_the_permalink( $target_post )
        );

        return rtrim( $replaced, '/' );
    }
