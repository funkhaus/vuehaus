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

 /*
  * Serialize ACF content with get_field to respect ACF custom formatting
  */
     function serialize_custom_acf_content($post_data){

         // check to make sure we have ACF installed
         if (!function_exists('get_fields')){
             return $post_data;
         }

         // get ACF fields attached to target post
         $acf_fields = get_fields($post_data['ID']);

         // prep to save serialized fields
         $post_data['acf'] = array();

         if( !empty($acf_fields) ){

             // serialize ACF fields
             foreach( $acf_fields as $name => $value ){
                 $post_data['acf'][$name] = $value;
             }

         }

         return $post_data;
     }
     add_filter('rez_serialize_post', 'serialize_custom_acf_content');

 /**
  *  Add pages with dev ID to site data
  *
  * @param array $input The nav item currently being processed by Rest-Easy
  */
     function add_dev_page_links_to_site_data($site_data){

         $args = array(
        	'posts_per_page'   => -1,
        	'orderby'          => 'menu_order',
        	'order'            => 'ASC',
            // get all non-empty custom_developer_id pages
            'meta_query'        => array(
                array(
                    'key'   => 'custom_developer_id',
                    'value' => array(''),
                    'compare'   => 'NOT IN'
                )
            ),
        	'post_type'        => 'page',
        );
        $posts_array = get_posts( $args );

        $site_data['devIds'] = array();

        foreach($posts_array as $dev_id_page){
            $key = $dev_id_page->custom_developer_id;
            $site_data['devIds'][$key] = wp_make_link_relative(get_permalink($dev_id_page));
        }

        return $site_data;
     }
     add_filter('rez_build_site_data', 'add_dev_page_links_to_site_data');

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
