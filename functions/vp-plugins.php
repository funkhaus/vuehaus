<?php
/*
 * This file handles the required plugins of the Vuepress theme
 */
    include_once get_template_directory() . '/functions/class-tgm-plugin-activation.php';

    function vuepress_register_required_plugins() {

        // Change this value to install a new version of Rest-Easy
        $latest_rest_easy = '1.40';

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
            )
        );

        tgmpa( $plugins, $config );
    }

    // Register required & recommended plugins
    add_action( 'tgmpa_register', 'vuepress_register_required_plugins' );
