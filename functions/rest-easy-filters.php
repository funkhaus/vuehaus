<?php
/*
 * Custom Rest-Easy filters go in this file
 * You can see the documentation here: https://github.com/funkhaus/Rest-Easy
 */

 /**
  *  Get the iFrame embed code
  *
  * @param array $input The post currently being processed by Rest-Easy
  */
  function custom_video_embed($input){

        if( isset($input['meta']['custom_video_url']) ) {
            $args = array(
                'width' => 1280
            );
            $embed = wp_oembed_get($input['meta']['custom_video_url'], $args);

            $input['meta']['custom_video_url_embed'] = $embed;
        }

        return $input;
    }
    add_filter('rez_serialize_post', 'custom_video_embed');


/**
 *  Serialize page siblings
 *
 * @param array $input The post currently being processed by Rest-Easy
 */
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


/**
 *  Serialize the Developer ID
 *
 * @param array $input The post currently being processed by Rest-Easy
 */
    function add_developer_id($input){
        $target = get_post($input['id']);
        $input['developerId'] = $target->custom_developer_id;
        return $input;
    }
    add_filter('rez_serialize_post', 'add_developer_id');


/**
 *  Serialize attachment video URL
 *
 * @param array $input The post currently being processed by Rest-Easy
 */
    function add_attachment_video_url($input){
        $target = get_post($input['ID']);
        $input['videoUrl'] = $target->custom_video_url;
        return $input;
    }
    add_filter('rez_serialize_attachment', 'add_attachment_video_url');
