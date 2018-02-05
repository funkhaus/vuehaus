<?php
/*
 * Add vue.js router to rest-easy data
 */
	function add_routes_to_json($jsonData){

        // Get the name of the category base. Default to "categories"
        $category_base = get_option('category_base');
		if( empty($category_base) ){
			$category_base = 'category';
		}

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
			$custom_template_routes[rez_remove_siteurl($page)] = $page->custom_vuepress_template;
		}

        // build out router table to be used with Vue
        $programmed_routes = array(

            // Per-site
            // '/path'                              => 'VueComponent',
            // '/path/:var'                         => 'ComponentWithVar'
            // '/path/*/:var'                       => 'WildcardAndVar'
            // path_from_dev_id('dev-id')  		=> 'DefinedByDevId',
		    // path_from_dev_id('dev-id', '/append-me') => 'DevIdPathPlusAppendedString',

			/*
			// Common Funkhaus setups
			// Directors - redirect to first child
			path_from_dev_id('directors') => array(
				'redirect'	=>  get_child_of_dev_id_path('directors')
			),
			// Director detail
			path_from_dev_id('directors', '/:director')					=> 'DirectorDetail',
			// Reel grid
			path_from_dev_id('directors', '/:director/:reel')			=> 'ReelGrid',
			// Video detail
			path_from_dev_id('directors', '/:director/:reel/:video')	=> 'VideoDetail',
			// About
			path_from_dev_id('about')	=> 'About',
			// Contact
			path_from_dev_id('contact')	=> 'Contact',
			// Contact region - redirect to parent
			path_from_dev_id('contact', '/:region')	=> array(
				'redirect'	=> path_from_dev_id('contact')
			),
			*/

            // Probably unchanging
            ''                                      => 'FrontPage',
            '/' . $category_base                    => 'Archive',
            '*'                                		=> 'Default'

        );

		$jsonData['routes'] = array_merge( $custom_template_routes, $programmed_routes );

		return $jsonData;
	}
	add_filter('rez_build_all_data', 'add_routes_to_json');
