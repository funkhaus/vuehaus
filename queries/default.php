<?php
    global $post;
    global $posts;

    // If we have children, get the target post and its children
    if( has_children( $post ) ){

        $formatted = serializer_standard($post);

        $args = array(
            'post_type'        => 'page',
            'orderby'          => 'menu_order',
            'posts_per_page'   => -1,
            'post_parent'      => $post->ID,
            'order'            => 'ASC'
        );
        $children = get_posts($args);

        $formatted['children'] = array();
        foreach( $children as $child ){
            $formatted['children'][] = serializer_standard($child);
        }

        $data = array( $formatted );


    } else {

        // If we don't have children, serialize all posts we received from the query.
        // This could be just the target post or a paginated array of posts on a blog homepage.
        $data = array_map( 'serializer_standard', $posts );

    }

    return $data;
