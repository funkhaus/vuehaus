<?php

	// Import Vuepress-specific functions
	include_once get_template_directory() . '/functions/vuepress.php';

/*
 * Setup image sizes
 */
	function custom_image_sizes(){
		// Enable post thumbnail support
		add_theme_support( 'post-thumbnails' );
		//set_post_thumbnail_size( 600, 400, true ); // Normal post thumbnails
		//add_image_size( 'banner-thumb', 566, 250, true ); // Small thumbnail size
		add_image_size( 'social-preview', 600, 315, true ); // Square thumbnail used by sharethis and facebook
		add_image_size( 'fullscreen', 1920, 1080, false ); // Fullscreen image size
	}
	add_action( 'after_setup_theme', 'custom_image_sizes' );

/*
 * Add custom metaboxes to the new/edit page
 */
    function custom_add_metaboxes($post_type, $post){
		//add_meta_box('custom_media_meta', 'Media Meta', 'custom_media_meta', 'page', 'normal', 'low');
		//add_meta_box('custom_second_featured_image', 'Second Featured Image', 'custom_second_featured_image', 'page', 'side', 'low');
    }
	add_action('add_meta_boxes', 'custom_add_metaboxes', 10, 2);

	// Build media meta box
	function custom_media_meta($post) {
		?>

        	<div class="custom-meta">
				<label for="video-url">Enter the video URL for this page:</label>
				<input id="video-url" class="short" title="This is needed for all video pages" name="custom_video_url" type="text" value="<?php echo $post->custom_video_url; ?>">
				<br/>
        	</div>

		<?php
	}

/*
 * Save metabox values
 */
    function custom_save_metabox($post_id){

        // check autosave
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if( isset($_POST['custom_video_url']) ) {
	        update_post_meta($post_id, 'custom_video_url', $_POST['custom_video_url']);
        }
        if( isset($_POST['second_post_thumbnail']) ) {
	        //update_post_meta($post_id, 'second_post_thumbnail', $_POST['second_post_thumbnail']);
        }

    }
    add_action('save_post', 'custom_save_metabox');

/*
 * Translate shortcode to Vue components.
 * Use a new copy of this function for each shortcode supported in wp-content.
 */
	function custom_shortcode_function( $atts ) {

		// Include default props here
        extract(shortcode_atts(array(
			// example:
			'title'         => ''
        ), $atts));

		// Props to pass to Vue component
		$props = 'title="' . $title . '"';

		return '<vue-component-name ' . $props . '/>';
	}
	add_shortcode( 'shortcode-name', 'custom_shortcode_function' );


// NOTE:
// Functions below are not enabled by default, but are used frequently enough to comment out in the boilerplate.


/*
 * Enqueue Custom Admin Scripts
 */
	function custom_admin_scripts() {
		//wp_register_script('site-admin', get_template_directory_uri() . '/static/js/admin.js', 'jquery', '1.0');
		//wp_enqueue_script('site-admin');
	}
	add_action( 'admin_enqueue_scripts', 'custom_admin_scripts' );

/*
 * Allow subscriber to see Private posts/pages
 */
	function add_theme_caps() {
	    // Gets the author role
	    $role = get_role('subscriber');

	    // Add capabilities
	    $role->add_cap('read_private_posts');
		$role->add_cap('read_private_pages');
	}
	//add_action( 'switch_theme', 'add_theme_caps');


/*
 * Change the [...] that comes after excerpts
 */
    function custom_excerpt_ellipsis( $more ) {
        return '...';
    }
    //add_filter('excerpt_more', 'custom_excerpt_ellipsis');
