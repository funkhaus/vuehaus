<?php
    // abort if no post to work off of
    if ( ! $post = get_post() ) return;

    the_post();

    // schema defaults
    $use_png = file_exists(dirname(__FILE__) . '/../screenshot.png');
    $extension = $use_png ? 'png' : 'jpg';
    $schema = array(
        '@context'      => 'http://schema.org',
        '@type'         => 'WebPage',
        'headline'      => get_bloginfo('description'),
        'url'           => get_bloginfo('url'),
        'thumbnailUrl'  => get_template_directory_uri() . '/screenshot.' . $extension
    );

    // helper to generate schema Person object
    function schema_generate_person($name){
        return array(
            '@type'     => 'Person',
            'name'      => $name
        );
    }

    // helper to generate schema Organization object
    function schema_generate_org($name = null, $logo = null){
        return array(
            '@type'     => 'Organization',
            'name'      => $name ? $name : get_bloginfo('name'),
            'logo'      => $logo ? $logo : get_template_directory_uri() . '/images/logo.svg'
        );
    }

    // set some helpful vars
    $image_id = false;
    $image_url = false;
    $image = wp_get_attachment_image_src($image_id, 'social-preview');
    if( $image ){
        $image_id = get_post_thumbnail_id( $post );
        $image_url = reset( $image );
    }
    $author = get_user_by('ID', $post->post_author);
    $queried_object = get_queried_object();
    $excerpt = get_the_excerpt($post);

    // build on schema conditionally
    switch (true) {

        // is non-home page
        case !is_front_page() and is_page():
            $schema['headline'] = get_the_title($post);
            $schema['url'] = get_the_permalink($post);
            $schema['thumbnailUrl'] = $image_url;
            $schema['description'] = $excerpt ? $excerpt : get_bloginfo('description');
            break;

        // is single post
        case is_single():
            $schema['@type'] = 'NewsArticle';
            $schema['headline'] = get_the_title($post);
            $schema['url'] = get_the_permalink($post);
            $schema['thumbnailUrl'] = $image_url;
            $schema['image'] = $image_url;
            $schema['dateCreated'] = get_the_date('Y-m-d');
            $schema['datePublished'] = get_the_date('Y-m-d');
            $schema['articleSection'] = '';
            $schema['description'] = get_the_excerpt($post);
            $schema['creator'] = $author ? schema_generate_person($author->display_name) : schema_generate_org();
            $schema['author'] = $author ? schema_generate_person($author->display_name) : schema_generate_org();
            $schema['publisher'] = schema_generate_org();
            $schema['keywords'] = wp_get_post_categories($post->ID, array(
                'fields' => 'names'
            ));
            break;

        // all archive pages
        case is_archive():
            $schema['@type'] = 'Blog';

            // is a term archive
            if ( property_exists($queried_object, 'term_id') ){
                $schema['headline'] = term_description($queried_object->term_id);
                $schema['url'] = get_term_link($queried_object->term_id);
                $schema['articleSection'] = $queried_object->name;
            }
            break;

        // blog archive, if different than front page
        case is_home() and !is_front_page():
            $schema['@type'] = 'Blog';
            $schema['url'] = get_the_permalink(get_option('page_for_posts'));
            break;

    }

?>

<!-- start schema.org definition -->
    <script type="application/ld+json">
        <?php echo json_encode($schema); ?>
    </script>
<!-- end schema.org -->
