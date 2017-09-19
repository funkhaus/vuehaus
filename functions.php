<?php
    include 'states.php';
	include 'queries/index.php';


/*
 * Setup WordPress
 */
	function custom_wordpress_setup() {

		// Enable tags for Pages (@see: https://wordpress.org/support/topic/enable-tags-screen-for-pages#post-29500520
		//register_taxonomy_for_object_type('post_tag', 'page');

	    // Enable excerpts for pages
	    add_post_type_support('page', 'excerpt');

	}
	add_action('init', 'custom_wordpress_setup');

/*
 * Setup theme
 */
	function custom_theme_setup() {

		// Enable post thumbnail support
		add_theme_support( 'post-thumbnails' );
		//set_post_thumbnail_size( 600, 400, true ); // Normal post thumbnails
		//add_image_size( 'banner-thumb', 566, 250, true ); // Small thumbnail size
	    add_image_size( 'social-preview', 600, 315, true ); // Square thumbnail used by sharethis and facebook

	    // Turn on menus
		add_theme_support('menus');

		// Enable HTML5 support
		add_theme_support('html5');

	}
	add_action( 'after_setup_theme', 'custom_theme_setup' );

    // Add Developer role
    function custom_theme_switch(){
        global $wp_roles;
        if ( ! isset( $wp_roles ) ){
            $wp_roles = new WP_Roles();
        }

        $admin_role = $wp_roles->get_role('administrator');

        add_role(
            'developer',
            __('Developer'),
            $admin_role->capabilities
        );

        // set initial user to Developer
        $user = new WP_User(1);
        $user->set_role('developer');
    }
    add_action('after_switch_theme', 'custom_theme_switch');


/*
 * Handle content width edge cases
 */
	function set_content_width() {
		global $content_width;
		if ( is_single() ) {
			$content_width = 960;
		} else {
			$content_width = 960;
		}
	}
	add_action( 'template_redirect', 'set_content_width' );



/*
 * Enqueue Custom Scripts
 */
    function custom_scripts() {
		wp_register_script('bundle', get_template_directory_uri() . '/static/bundle.js', array(), '1.0');
		wp_enqueue_script('bundle', null, array(), false, true);

		$queryData = load_query_data();
		wp_localize_script('bundle', 'queryData', $queryData);
    }
    add_action('wp_enqueue_scripts', 'custom_scripts', 10);


/*
 * Enqueue Custom Admin Scripts
 */
	function custom_admin_scripts() {
		//wp_register_script('site-admin', get_template_directory_uri() . '/static/js/admin.js', 'jquery', '1.0');
		//wp_enqueue_script('site-admin');
	}
	add_action( 'admin_enqueue_scripts', 'custom_admin_scripts' );

/*
 * Output JSON data
 */

	function output_api_data(){

		$json_header = $_SERVER['CONTENT_TYPE'] == 'application/json';
		$json_type = $_REQUEST['content'] == 'json';

		if ( $json_header || $json_type ){

			header('Content-Type: application/json');

			$data = load_query_data();
			echo json_encode($data);
			exit();

		}

	}
	add_action('wp', 'output_api_data');

/*
 * Custom Background Classes
 */
    // Add specific CSS class by filter
    function custom_class_names($classes) {
        global $post;
        $state = get_conditional_state($post);

        if ( $state ) {
            $classes[] = $state;
        }

		// Mobile Detects
		if( wp_is_mobile() ) {
			$classes[] = 'is-mobile';
		} else {
			$classes[] = 'not-mobile';
		}

    	return $classes;
    }
    add_filter('body_class','custom_class_names');



/*
 * Style login page and dashboard
 */
	// Style the login page
	function custom_loginpage_logo_link($url) {
	     // Return a url; in this case the homepage url of wordpress
	     return get_bloginfo('url');
	}
	function custom_loginpage_logo_title($message) {
	     // Return title text for the logo to replace 'wordpress'; in this case, the blog name.
	     return get_bloginfo('name');
	}
	function custom_loginpage_styles() {
        wp_enqueue_style( 'login_css', get_template_directory_uri() . '/static/css/login.css' );
	}
	function custom_admin_styles() {
        wp_enqueue_style('admin-stylesheet', get_template_directory_uri() . '/static/css/admin.css');
	}
	add_filter('login_headerurl','custom_loginpage_logo_link');
	add_filter('login_headertitle','custom_loginpage_logo_title');
	add_action('login_head','custom_loginpage_styles');
    add_action('admin_print_styles', 'custom_admin_styles');



/*
 * Add post thumbnail into RSS feed
 */
    function rss_post_thumbnail($content) {
        global $post;

        if( has_post_thumbnail($post->ID) ) {
            $content = '<p><a href='.get_permalink($post->ID).'>'.get_the_post_thumbnail($post->ID).'</a></p>'.$content;
        }

		return $content;
	}
	add_filter('the_excerpt_rss', 'rss_post_thumbnail');

/*
 * Custom conditional function. Used to test if a post is an ascendent or descendent of another.
 */
    function is_tree($tree_id, $target_post = null) {

        // get full post object
        $target_post = get_post($target_post);

        // get all post ancestors
        $ancestors = get_ancestors($target_post->ID, $target_post->post_type);

        // if ID is target post OR in target post tree, return true
        return ( ($target_post->ID == $tree_id) or in_array($tree_id, $ancestors) );
    }



/*
 * Custom conditional function. Used to test if current page has children.
 */
    function has_children($target_post = null) {

        // get full post object
        $target_post = get_post($target_post);

        // Check if the post/page has a child
        $args = array(
        	'post_parent' 		=> $target_post->ID,
        	'post_type'			=> $target_post->post_type,
        	'posts_per_page'	=> 1
        );
        $children = get_posts($args);

        return !empty($children);
    }

/*
 * Next page/post ID
 */
	function get_next_page_id($target_post = null, $exclude = null, $loop = true) {
		$target_post = get_post($target_post);

		// set current post type
		$post_type = get_post_type( $target_post );

		// Set vars
		$current_project_id = $target_post->ID;
		$cache_key = 'all_pages_parent_'.$current_project_id;

		// Check for cached $pages
		$pages = get_transient( $cache_key );
		if ( empty( $pages ) ){
			$args = array(
				'post_type'         => $post_type,
				'order'             => 'ASC',
				'orderby'           => 'menu_order',
				'post_parent'       => $target_post->post_parent,
				'fields'            => 'ids',
				'posts_per_page'    => -1,
				'post__not_in' 		=> $exclude
			);
			$pages = get_posts($args);

			// Save cache
			set_transient($cache_key, $pages, 30 );
        }

		$current_key = array_search($current_project_id, $pages);
		$output = false;

		if( isset($pages[$current_key+1]) ) {
			// Next page exists
			$output = $pages[$current_key+1];

		// No next page, should we loop to first?
		} elseif ( $loop ) {

			// Get first page
			$output = $pages[0];
		}

		return $output;
	}


/*
 * Previous page/post ID
 */
    function get_previous_page_id($target_post = null, $exclude = null, $loop = true) {
        $target_post = get_post($target_post);

		// set current post type
		$post_type = get_post_type( $target_post );

		// Set vars
        $current_project_id = $target_post->ID;
        $cache_key = 'all_pages_parent_'.$current_project_id;

        // Check for cached $pages
        $pages = get_transient( $cache_key );
        if ( empty( $pages ) ){
			$args = array(
				'post_type'         => $post_type,
				'order'             => 'ASC',
				'orderby'           => 'menu_order',
				'post_parent'       => $target_post->post_parent,
				'fields'            => 'ids',
				'posts_per_page'    => -1,
				'post__not_in' 		=> $exclude
			);
			$pages = get_posts($args);

			// Save cache
			set_transient($cache_key, $pages, 30 );
        }

        $current_key = array_search($current_project_id, $pages);
		$output = false;

        if( isset($pages[$current_key-1]) ) {
            // Previous page exists
            $output = $pages[$current_key-1];

		// No previous page, should we loop to last?
        } elseif ( $loop ) {

			// Get last page
			$output = array_pop($pages);
        }

		return $output;
    }


/*
 * Redirect to first child
 */
    function get_first_child_id( $target_post = null ) {

        $target_post = get_post($target_post);

        $id = false;
        $args = array(
            'post_type'         => get_post_type($target_post),
            'post_parent'       => $target_post->ID,
            'order'             => 'ASC',
            'orderby'           => 'menu_order',
            'fields'            => 'ids',
            'posts_per_page'    => 1
        );
        $children = get_posts($args);

        if( isset($children[0]) ) {
            $id = $children[0];
        }

        return $id;
    }


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
 * Disable Rich Editor on certain pages
 */
	function disabled_rich_editor($allow_rich_editor) {
		global $post;

		if($post->post_name == 'contact') {
			return false;
		}
		return $allow_rich_editor;
	}
	//add_filter( 'user_can_richedit', 'disabled_rich_editor');


/*
 * Change the [...] that comes after excerpts
 */
    function custom_excerpt_ellipsis( $more ) {
        return '...';
    }
    //add_filter('excerpt_more', 'custom_excerpt_ellipsis');

/*
 * Allow SVG uploads
 */
    function add_mime_types($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    //add_filter('upload_mimes', 'add_mime_types');


/*
 * Enqueue Custom Gallery
 */
	function custom_gallery($atts) {
		if ( !is_admin() ) {
			include('parts/gallery.php');
		}
		return $output;
	}
	//add_shortcode('gallery', 'custom_gallery');


/*
 * Get image dimensions and calculate padding percentage based on aspect ratio
 */
    function get_responsive_image_padding($target_attachment = null, $size = 'post-thumbnail'){

        // Always have an image to work with
        if ( $target_attachment ){
            $target_attachment = get_post($target_attachment);
        } else {
            $post = get_post();
            $target_attachment = get_post( get_post_thumbnail_id($post->ID) );
        }

        // get src data of attachment, set dimensions
        $img_data = wp_get_attachment_image_src($target_attachment->ID, $size);

        // About if no attachment found
        if( !$img_data ) {
            return 0;
        }

        $width = $img_data[1];
        $height = $img_data[2];

        // return percentage for padding
        return ($height / $width) * 100;
    }

/*
 * Return the URL to a given attachment, while respecting size
 */
    function get_custom_attachment_url($attachment_id = false, $size = 'post-thumbnail') {
        if( !$attachment_id ) {
            global $post;
            $attachment_id = get_post_thumbnail_id($post->ID);
        }
        $attachment_data = wp_get_attachment_image_src( $attachment_id, $size);
        if( isset($attachment_data[0]) ) {
            return $attachment_data[0];
        }
        return '';
    }

/*
 * Return the URL to a given post's featured image, respecting size
 */
    function get_featured_image_url($target_post = null, $size = 'post-thumbnail') {
        $attachment_id = get_post_thumbnail_id($target_post);
        return get_custom_attachment_url($attachment_id, $size);
    }


/*
 * Split and wrap title
 */
    function get_split_title($post = null) {
        $post = get_post($post);

        $title = get_the_title($post->ID);
        $lines = explode(' &#8211; ', $title);
        $output = false;
        $count = 0;

        foreach( $lines as $line ) {
            $count++;
            $output .= '<span class="line line-'.$count.'">'.$line.'</span> ';
        }

        return $output;
    }


/*
 * Add custom metabox to the new/edit page
 */
    function custom_add_metaboxes($post_type, $post){

		// add_meta_box('custom_media_meta', 'Media Meta', 'custom_media_meta', 'page', 'normal', 'low');
		// add_meta_box('custom_second_featured_image', 'Second Featured Image', 'custom_second_featured_image', 'page', 'side', 'low');

        // only render the following for users with Developer role
        if( !user_is_developer() ) return;

        add_meta_box('custom_dev_meta', 'Developer Meta', 'custom_dev_meta', 'page', 'normal', 'low');

    }
	add_action('add_meta_boxes', 'custom_add_metaboxes', 10, 2);

	// Build media meta box
	function custom_media_meta($post) {

		?>
        	<div class="custom-meta">
				<label for="video-url">Enter the video URL for this page:</label>
				<input id="video-url" class="short" title="This is needed for all video pages" name="_custom_video_url" type="text" value="<?php echo $post->_custom_video_url; ?>">
				<br/>

        	</div>

		<?php
	}

    // Build dev meta box (only for users with Developer role)
	function custom_dev_meta($post) {

		?>
            <!--<p>Page state (via <code>get_conditional_state()</code>): <code><?php echo get_conditional_state(); ?></code></p> -->

            <div class="custom-meta">
                <label for="custom-guid">Enter the GUID for this page:</label>
                <input id="custom-guid" class="short" title="GUID" name="_custom_guid" type="text" value="<?php echo $post->_custom_guid; ?>">
                <br/><br/>

            </div>

        	<!-- <div class="custom-meta">
				<label for="state-name">Enter the state for this page <br/> (fallback: <code><?php echo get_conditional_state($post); ?></code>):</label>
				<input id="state-name" class="short" title="State name" name="_custom_state_name" type="text" value="<?php echo $post->_custom_state_name; ?>">
				<br/><br/>

        	</div> -->

            <div class="custom-meta">
				<label for="custom-lock">Prevent non-dev deletion:</label>
				<input id="custom-lock" class="short" title="Prevent deletion" name="_custom_lock" type="checkbox" <?php if( $post->_custom_lock ) echo 'checked'; ?>>
				<br/>

        	</div>

		<?php
	}

    // Prevent non-dev from deleting locked pages/posts
    function check_custom_post_lock( $target_post ){
        $target_post = get_post($target_post);

        if( !user_is_developer() and $target_post->_custom_lock ){
            echo 'Only a user with the Developer role can delete this page.<br/><br/>';
            echo '<a href="javascript:history.back()">Back</a>';
            exit;
        }
    }
    add_action('wp_trash_post', 'check_custom_post_lock', 10, 1);
    add_action('before_delete_post', 'check_custom_post_lock', 10, 1);

    // Second featured image uploader (requires changes to admin.js too).
    // @see: https://codex.wordpress.org/Javascript_Reference/wp.media
    function custom_second_featured_image($post){

        // Meta key (need to update the save_metabox function below to reflect this too!)
        $meta_key = '_second_post_thumbnail';

        // Get WordPress' media upload URL
        $upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );

        // See if there's a media id already saved as post meta
        $image_id = get_post_meta( $post->ID, $meta_key, true );

        // Get the image src
        $image_src = wp_get_attachment_image_src( $image_id, 'post-thumbnail' );

        // For convenience, see if the array is valid
        $has_image = is_array( $image_src );

        ?>

            <div class="custom-meta custom-image-uploader">

                <!-- A hidden input to set and post the chosen image id -->
                <input class="custom-image-id" name="<?php echo $meta_key; ?>" type="hidden" value="<?php echo $image_id; ?>" />

                <!-- Image container, which is manipulated with js -->
                <div class="custom-image-container">
                    <?php if ( $has_image ) : ?>
                        <img src="<?php echo $image_src[0] ?>"/>
                    <?php endif; ?>
                </div>

                <!-- Add & remove image links -->
                <p class="hide-if-no-js">
                    <a class="upload-custom-image <?php if ( $has_image  ) { echo 'hidden'; } ?>" href="<?php echo $upload_link ?>">
                        <?php _e('Set second featured image') ?>
                    </a>
                    <a class="delete-custom-image <?php if ( ! $has_image  ) { echo 'hidden'; } ?>" href="#">
                        <?php _e('Remove image') ?>
                    </a>
                </p>

            </div>

        <?php
    }

/*
 * Get second post thumbnail (mimic functionality of get_the_post_thumbnail)
 */
    function get_the_second_post_thumbnail( $post = null, $size = 'post-thumbnail', $attr = '' ) {
        $post = get_post($post);
        $image = $post->_second_post_thumbnail;
        $classes = 'attachment-second-post-thumbnail size-full wp-second-post-image';
        if ( $attr == '' ) {
            // Create $attr array if none exists yet
            $attr = array('class' => $classes);
        } else if ( !empty($attr['class']) ){
            // Append to $attr['class'] if it exists
            $attr['class'] .= ' ' . $classes;
        } else if ( gettype($attr) == 'array' ) {
            // Append to $attr array if ['class'] doesn't exist yet
            $attr['class'] = $classes;
        }
        return wp_get_attachment_image( $image, $size, false, $attr );
    }


/*
 * Save the metabox vaule
 */
    function custom_save_metabox($post_id){

        // check autosave
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if( isset($_POST['_custom_video_url']) ) {
	        update_post_meta($post_id, '_custom_video_url', $_POST['_custom_video_url']);
        }
        if( isset($_POST['_custom_guid']) ) {
            update_post_meta($post_id, '_custom_guid', $_POST['_custom_guid']);
        }
        if( isset($_POST['_custom_state_name']) ) {
            update_post_meta($post_id, '_custom_state_name', $_POST['_custom_state_name']);
        }
        if( isset($_POST['_second_post_thumbnail']) ) {
	        //update_post_meta($post_id, '_second_post_thumbnail', $_POST['_second_post_thumbnail']);
        }

        // these values will only be updated if the current user is a Developer
        if( !user_is_developer() ) return;

        if( isset($_POST['_custom_lock']) ) {
            $value = False;
            if( $_POST['_custom_lock'] == 'on' ){
                $value = True;
            }
            update_post_meta($post_id, '_custom_lock', $value);
        } else {
            update_post_meta($post_id, '_custom_lock', False);
        }

    }
    add_action('save_post', 'custom_save_metabox');

    function user_is_developer(){
        $roles = wp_get_current_user()->roles;
        return in_array( 'developer', $roles );
    }
