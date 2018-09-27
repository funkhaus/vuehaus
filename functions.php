<?php
/*
 * This file is the main entry point for WordPress functions.
 * The top set of files generally require edits.
 */
    // Builds Vue router
    include_once get_template_directory() . '/functions/router.php';

    // Custom Rest-Easy filters
    include_once get_template_directory() . '/functions/rest-easy-filters.php';

    // Misc WordPress functions
    include_once get_template_directory() . '/functions/wp-functions.php';

    // Handles what image sizes WordPress should generate server side
    include_once get_template_directory() . '/functions/images.php';

    // Handles the server side processing of WordPress shortcodes
    include_once get_template_directory() . '/functions/shortcodes.php';

    // Provides list of custom Vue Templates that the user can select from a drop-down menu
    include_once get_template_directory() . '/functions/custom-vuepress-templates.php';

    // Defines the UI for custom meta boxes in WordPress
    include_once get_template_directory() . '/functions/meta-boxes.php';

    //Add additional ACF functionality
    include_once get_template_directory() . '/functions/acf-functions.php';

/*
 * Generally you don't have to edit any of the files below
 */
     // Handles VP functions and plugin dependencies (Rest-Easy and recommended Nested Pages)
     include_once get_template_directory() . '/functions/vp-functions.php';

     // Handles Developer role
     include_once get_template_directory() . '/functions/developer-role.php';
