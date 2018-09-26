<?php
/*
 * This file handles the required plugins of the Vuepress theme
 */
    include_once get_template_directory() . '/functions/class-tgm-plugin-activation.php';

    function vuepress_register_required_plugins() {

        // Change these values to install new versions of FH plugins
        $latest_rest_easy = '1.46';
        $latest_focushaus = '1.0.5';

        $config = array(
            'id'           => 'vuepress',              // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug'  => 'themes.php',            // Parent menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
        );

        $plugins = array(
            array(
                'name'      => 'Rest-Easy',
                'slug'      => 'rest-easy',
                'source'    => 'https://github.com/funkhaus/Rest-Easy/archive/master.zip',
                'version'   => $latest_rest_easy
            ),
            array(
                'name'      => 'Nested Pages',
                'slug'      => 'wp-nested-pages',
                'required'	=> false
            ),
            array(
                'name'      => 'Focushaus',
                'slug'      => 'focushaus',
                'source'    => 'https://github.com/funkhaus/focushaus/archive/master.zip',
                'version'   => $latest_focushaus
            )
        );

        tgmpa( $plugins, $config );
    }

    // Register required & recommended plugins
    add_action( 'tgmpa_register', 'vuepress_register_required_plugins' );


    function get_custom_template_routes(){

		// Find all pages with custom_vuepress_template set
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'meta_key'         => 'custom_vuepress_template',
			'meta_value'       => '',
			'post_type'        => array('post', 'page')
		);
		$all_pages_with_custom_templates = get_posts($args);

		// Filter out pages with vuepress custom template set to Default
		$filtered_pages_with_custom_templates = array_filter(
			$all_pages_with_custom_templates,
			function( $page ){
				return $page->custom_vuepress_template !== 'Default';
			}
		);

		// Build out router for pages with custom templates
		$custom_template_routes = array();
		foreach( $filtered_pages_with_custom_templates as $page ){
			$page_url = get_permalink($page);
			$custom_template_routes[wp_make_link_relative($page_url)] = $page->custom_vuepress_template;
		}

		return $custom_template_routes;
    }
