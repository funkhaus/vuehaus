<?php

    // Builds Vue router
    include_once get_template_directory() . '/functions/router.php';
    // Custom Rest-Easy filters
    include_once get_template_directory() . '/functions/rest-easy-filters.php';
    // Handles plugin dependencies (Rest-Easy and recommended Nested Pages)
    include_once get_template_directory() . '/functions/vuepress-plugins.php';
    // Handles Developer role
    include_once get_template_directory() . '/functions/developer-role.php';
    // Misc WordPress functions
    include_once get_template_directory() . '/functions/wp-functions.php';
