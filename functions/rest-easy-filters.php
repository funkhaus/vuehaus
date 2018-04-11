<?php
/*
 * Custom Rest-Easy filters go in this file
 * You can see the documentation here: https://github.com/funkhaus/Rest-Easy
 * Common examples of filters to add in this file can be found here: https://github.com/funkhaus/vuepress/tree/master/functions
 */

 /**
  *  Add dev_id to nav items as they are serialized
  *
  * @param array $input The nav item currently being processed by Rest-Easy
  */
     function add_dev_id_to_nav_items($nav_item_data){

         if ( isset($nav_item_data['id']) ) {
             // get full nav item
             $object_type = get_post_meta($nav_item_data['id'], '_menu_item_object', true);
             $object_id = get_post_meta($nav_item_data['id'], '_menu_item_object_id', true);

             // if nav item is a page, add Dev ID
             if ( $object_type == 'page' ) {
                 $nav_item_data['devId'] = get_post_meta($object_id, 'custom_developer_id', true);
             }
         }

         return $nav_item_data;
     }
     add_filter('rez_serialize_nav_item', 'add_dev_id_to_nav_items');

/**
 *  Serialize page siblings
 *
 * @param array $input The post currently being processed by Rest-Easy
 */

 // NOTE: This is a common Rest-Easy filter, but including it by default can slow
 //         down load times on large sites. Uncomment to use, but it's best to
 //         include some extra checks so this doesn't run on every page.

    // function add_page_siblings($related, $target = null){
    //     $target = get_post($target);
    //     $args = array(
    //         'posts_per_page'       => -1,
    //         'orderby'              => 'menu_order',
    //         'order'                => 'ASC',
    //         'exclude'              => array( $target->ID ),
    //         'post_type'            => 'page',
    //         'post_parent'          => $target->post_parent
    //     );
    //     $siblings = get_posts($args);
    //
    //     // apply the serialize_object filter to all siblings
    //     $related['siblings'] = array_map(
    //         function ($sibling) { return apply_filters('rez_serialize_object', $sibling); },
    //         $siblings
    //     );
    //
    //     // return modified data
    //     return $related;
    // }
    // add_filter('rez_gather_related', 'add_page_siblings', 10, 2);
