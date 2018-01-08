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

        // build out router table to be used with Vue
        $jsonData['routes'] = array(

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

		return $jsonData;
	}
	add_filter('rez_build_all_data', 'add_routes_to_json');
