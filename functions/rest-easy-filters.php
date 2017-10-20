<?php
    // Custom Rest-Easy filters here

    // Example: Serialize video meta
    function add_video_meta($data){
        $data['preview_video'] = get_post_meta($data['id'], 'custom_preview_url', true);
        $data['featured_video'] = wp_oembed_get(get_post_meta($data['id'], 'custom_video_url', true));
        return $data;
    }
    add_filter('rez_serialize_post', 'add_video_meta');

    // Example: Serialize page siblings
    function add_page_siblings($related){
        $target = get_post($related['id']);
        $args = array(
            'posts_per_page'       => -1,
            'orderby'              => 'menu_order',
            'order'                => 'ASC',
            'exclude'              => array( $target->ID ),
            'post_type'            => 'page',
            'post_parent'          => $target->post_parent
        );
        $siblings = get_posts($args);

        // apply the serialize_object filter to all siblings
        $related['siblings'] = array_map(
            function ($sibling) { return apply_filters('rez_serialize_object', $sibling); },
            $siblings
        );

        // return modified data
        return $related;
    }
    add_filter('rez_gather_related', 'add_page_siblings');
