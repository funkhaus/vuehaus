<?php
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
 * Set custom page title
 */
    function alter_wordpress_title( $title, $sep ) {
        return get_bloginfo('name') . $title;
    }
    add_filter('wp_title', 'alter_wordpress_title', 10, 2);

/*
 * Setup theme
 */
    function custom_theme_setup() {

	    // Turn on menus
		add_theme_support('menus');

		// Enable HTML5 support
		add_theme_support('html5');

	}
	add_action( 'after_setup_theme', 'custom_theme_setup' );

/*
 * Enqueue any Custom Admin Scripts
 */
	function custom_admin_scripts() {
		//wp_register_script('site-admin', get_template_directory_uri() . '/static/js/admin.js', 'jquery', custom_latest_timestamp());
		//wp_enqueue_script('site-admin');
	}
	//add_action( 'admin_enqueue_scripts', 'custom_admin_scripts' );


/*
 * Enqueue Custom Scripts
 */
    function custom_scripts() {
		$has_bundle = file_exists(get_template_directory() . '/static/bundle.js');
		$has_dev_bundle = file_exists(get_template_directory() . '/static/bundle.dev.js');

		// if WP_DEBUG is on, prefer dev bundle but fallback to prod
		// do the opposite when WP_DEBUG is off.
		$bundle_path = $has_bundle ? '/static/bundle.js' : '/static/bundle.dev.js';
		if ( WP_DEBUG ){
			$bundle_path = $has_dev_bundle ? '/static/bundle.dev.js' : '/static/bundle.js';
		}

		// Enqueue proper bundle
		wp_enqueue_script('bundle', get_template_directory_uri() . $bundle_path, null, custom_latest_timestamp(), true);
    }
    add_action('wp_enqueue_scripts', 'custom_scripts', 10);


/*
 * Convenience function to generate timestamp based on latest edits. Used to automate cache updating
 */
    function custom_latest_timestamp() {

		// set base, find top level assets of static dir
        $base =  get_template_directory();
		$assets = array_merge(glob($base . '/static/*.js'), glob($base . '/static/*.css'));

		// get m time of each asset
		$stamps = array_map(function($path){
			return filemtime($path);
		}, $assets);

		// if valid return time of latest change, otherwise current time
        return rsort($stamps) ? reset($stamps) : time();
    }


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
 * Custom conditional function. Used to get the parent and all it's child.
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
 * Allow SVG uploads
 */
    function add_mime_types($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    //add_filter('upload_mimes', 'add_mime_types');


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
    add_filter('excerpt_more', 'custom_excerpt_ellipsis');


/*
 * Add Google Analytics tracking settings to admin dashboard
 */
    function my_general_section() {
        add_settings_section(
            'vp_google_analytics_section',  // Section ID
            'Google Analytics Tracking IDs',        // Section Title
            'vp_google_analytics_section', // Callback
            'general'                      // This makes the section show up on the General Settings Page
        );

        add_settings_field(
            'ga_tracking_code_1',   // Option ID
            'Tracking ID #1',       // Label
            'vp_google_analytics_settings', // !important - This is where the args go!
            'general',                      // Page it will be displayed (General Settings)
            'vp_google_analytics_section',  // Name of our section
            array(
                'ga_tracking_code_1' // Should match Option ID
            )
        );

        add_settings_field(
            'ga_tracking_code_2',   // Option ID
            'Tracking ID #2',       // Label
            'vp_google_analytics_settings', // !important - This is where the args go!
            'general',                      // Page it will be displayed (General Settings)
            'vp_google_analytics_section',  // Name of our section
            array(
                'ga_tracking_code_2' // Should match Option ID
            )
        );

        register_setting('general','ga_tracking_code_1', 'esc_attr');
        register_setting('general','ga_tracking_code_2', 'esc_attr');
    }
    add_action('admin_init', 'my_general_section');


/*
 * Settings callbacks that build the Analytics markup
 */
    function vp_google_analytics_section() {
        echo '<p>Enter Google Analytics tracking codes. Uses the <code>gtag.js</code> tracking method.</p>';
    }

    function vp_google_analytics_settings($args) {
        $option = get_option($args[0]);
        echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" placeholder="UA-12345678-1"/>';
    }

/*
 * Add theme options page
 */
    // if( function_exists('acf_add_options_page') ) {
    // 	acf_add_options_page(array(
    // 		'page_title' 	=> 'Theme Settings',
    // 		'menu_title'	=> 'Theme Settings',
    // 		'menu_slug' 	=> 'theme-settings'
    // 	));
    // }
