<?php
/*
 * Custom Rest-Easy filters go in this file
 * You can see the documentation here: https://github.com/funkhaus/Rest-Easy
 * Common examples of filters to add in this file can be found here: https://github.com/funkhaus/vuepress/tree/master/functions
 */

/**
 *  Serialize page siblings
 *
 * @param array $input The post currently being processed by Rest-Easy
 */
    function add_page_siblings($related, $target = null){
        $target = get_post($target);
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
    add_filter('rez_gather_related', 'add_page_siblings', 10, 2);
