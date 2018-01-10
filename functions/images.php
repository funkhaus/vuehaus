<?php
/*
 * Setup image sizes that WordPress should generate on the server.
 */
	function custom_image_sizes(){
		// Enable post thumbnail support
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 960, 540, true ); // Normal post thumbnails
		//add_image_size( 'banner-thumb', 566, 250, true ); // Small thumbnail size
		add_image_size( 'social-preview', 600, 315, true ); // Square thumbnail used by sharethis and facebook
		add_image_size( 'fullscreen', 1920, 1080, false ); // Fullscreen image size
	}
	add_action( 'after_setup_theme', 'custom_image_sizes' );
