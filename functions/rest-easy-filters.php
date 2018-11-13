<?php
/*
 * Custom Rest-Easy filters go in this file
 * You can see the documentation here: https://github.com/funkhaus/Rest-Easy
 * Common examples of filters to add in this file can be found here: https://github.com/funkhaus/vuehaus/tree/master/functions
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

/*
 * Add Focushaus focal point and Funky-Colors colors to ACF images
 */
    function add_focal_point_to_acf_images($target){

        if( function_exists('get_offset_x') ){
            $target['focus'] = array(
                'x' => get_offset_x( $target['ID'] ),
                'y' => get_offset_y( $target['ID'] )
            );
        }

        if( function_exists('get_primary_image_color') ){
            $target['primary_color'] = get_primary_image_color($target['ID']);
            $target['secondary_color'] = get_second_image_color($target['ID']);
        }

        $image['videoSrc'] = $target->custom_video_url;

        return $target;
    }
    add_filter('acf/format_value/type=image', 'add_focal_point_to_acf_images', 20, 4);

/*
 * Add Focushaus focal point and Funky-Colors colors to ACF gallery images
 */
    function add_image_data_to_acf_galleries($value){

        foreach ($value as $index => &$image) {
            $target_post = get_post($image['id']);

            if( function_exists('get_offset_x') ){
                $image['focus'] = array(
                    'x' => get_offset_x( $image['id'] ),
                    'y' => get_offset_y( $image['id'] )
                );
            }
            if( function_exists('get_primary_image_color') ){
                $image['primary_color'] = get_primary_image_color($image['id']);
                $image['secondary_color'] = get_second_image_color($image['id']);
            }

            // add video to gallery image
            $image['meta']['custom_video_url'] = $target_post->custom_video_url;

        }
        return $value;
    }
    add_filter('acf/format_value/type=gallery', 'add_image_data_to_acf_galleries', 20, 4);

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

// Add theme options to site data
// function add_theme_options_to_site($site_data){
//     if ( function_exists('the_field') ){
//         $site_data['themeOptions'] = array(
//             'singleText'                => get_field('single_text', 'option'),
//             'multilineText'             => explode("\n", get_field('multiline_text', 'option')),
//         );
//     }
//     return $site_data;
// }
// add_filter('rez_build_site_data', 'add_theme_options_to_site');

    // Adds adjacent posts when they are unset due to recent bug in wordpress.
    //function add_adjacent_posts($related, $post = null) {
    //      if (is_null($related['next']) && is_null($related['prev'])) {
    //          $prev = get_posts(array(
    //              'post_type'            =>      'post',
    //              'posts_per_page'       =>      1,
    //              'date_query'           =>      array(
    //                  'after'    =>  $post->post_date
    //              )
    //          ));
    //          $next = get_posts(array(
    //              'post_type'            =>      'post',
    //              'posts_per_page'       =>      1,
    //              'date_query'           =>      array(
    //                  'before'    =>  $post->post_date
    //              )
    //          ));
    //         if (count($next) > 0){
    //              $related['next'] = apply_filters('rez_serialize_object', $next[0]);
    //         }
    //         if (count($prev) > 0){
    //             $related['prev'] = apply_filters('rez_serialize_object', $prev[0]);
    //         }
    //      }
    //      return $related;
    //  }
    //  add_filter('rez_gather_related', 'add_adjacent_posts', 10, 2);

    /**
     *  Add WP-Shopify options to site data
     *
     * @param array $site_data The site data currently being processed by Rest-Easy
     */
    function add_wps_options_to_site_data($site_data){
        if ( function_exists('get_wshop_domain') ){
            $site_data['storefrontToken'] = get_wshop_api_key();
            $site_data['shopifyDomain'] = get_wshop_domain();
        }
        return $site_data;
    }
    add_filter('rez_build_site_data', 'add_wps_options_to_site_data');
