<?php
    global $post;

    $args = array(
    	'posts_per_page'   => -1,
    	'orderby'          => 'menu_order',
    	'order'            => 'ASC',
    	'post_type'        => 'page',
    	'post_parent'      => $post->ID
    );
    $slides = get_posts($args);

    $export = $slides;
