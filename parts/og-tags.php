<?php
    the_post();
    /*
     * Build the image URL
     */
        $use_png = file_exists(dirname(__FILE__) . '/../screenshot.png');
        $extension = $use_png ? 'png' : 'jpg';
        $shared_image = get_template_directory_uri() . "/screenshot." . $extension;
        if( is_single() || is_page() ) {
            // If page or is single, set the shared image to the post thumbnail.
            $image_id = get_post_thumbnail_id();

            if( !empty($image_id) ) {
                $image_url = wp_get_attachment_image_src($image_id, 'social-preview');
                $shared_image = $image_url[0];
            }
        }

    /*
     * This builds the summary text.
     */
        $summary = get_bloginfo('description');
        if( is_single() || is_page() ) {
            // If page has no children or is single, set the summary to the excerpt.
            $summary = get_the_excerpt();
            if( empty($summary) ) {
                $summary = wp_trim_excerpt( strip_shortcodes($post->post_content) );
            }
        }

        // Remove any links, tags or line breaks from summary
        $summary = strip_tags($summary);
        $summary = esc_attr($summary);
        $summary = preg_replace('!\s+!', ' ', $summary);

    /*
     * Build permalink URL
     */
        $url = get_permalink($post->ID);
        if( is_front_page() ) {
            $url = get_bloginfo('url');
        }
?>

<meta name="description" content="<?php echo empty($summary) ? get_bloginfo('description') : $summary; ?>" />

<!-- Start Open Graph Meta Tags -->
    <meta property="og:title" content="<?php wp_title(''); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo get_permalink($post->ID); ?>" />
    <meta property="og:image" content="<?php echo $shared_image; ?>" />
    <meta property="og:description" content="<?php echo empty($summary) ? get_bloginfo('description') : $summary; ?>" />
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
<!-- End Open Graph Meta Tags -->
