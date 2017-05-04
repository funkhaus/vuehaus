<?php
    global $post;

    $formatted = serializer_standard($post);

    $next = get_next_page_id($post);
    $prev = get_previous_page_id($post);

    $formatted['next'] = serializer_standard($next);
    $formatted['prev'] = serializer_standard($prev);

    $data = array( $formatted );
