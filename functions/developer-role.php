<?php

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
     * Disable Rich Editor on certain pages
     */
    function disabled_rich_editor($allow_rich_editor) {
        global $post;

        if($post->_custom_hide_richedit === 'on') {
            return false;
        }
        return true;
    }
    add_filter( 'user_can_richedit', 'disabled_rich_editor');

    /*
     * Add developer metaboxes to the new/edit page
     */
    function custom_add_developer_metaboxes($post_type, $post){
        if( !user_is_developer() ) return;

        add_meta_box('custom_dev_meta', 'Developer Meta', 'custom_dev_meta', 'page', 'normal', 'low');
    }
    add_action('add_meta_boxes', 'custom_add_developer_metaboxes', 10, 2);


    // Build dev meta box (only for users with Developer role)
    function custom_dev_meta($post) {

        ?>
            <div class="custom-meta">
                <label for="custom-developer-id">Enter the Developer ID for this page:</label>
                <input id="custom-developer-id" class="short" title="Developer ID" name="custom_developer_id" type="text" value="<?php echo $post->custom_developer_id; ?>">
                <br/>

                <label for="custom-lock">Prevent non-dev deletion:</label>
                <input id="custom-lock" class="short" title="Prevent deletion" name="_custom_lock" type="checkbox" <?php if( $post->_custom_lock ) echo 'checked'; ?>>
                <br/>

                <label for="custom-richedit">Hide rich editor:</label>
                <input id="custom-richedit" class="short" title="Hide rich editor" name="_custom_hide_richedit" type="checkbox" <?php if( $post->_custom_hide_richedit === 'on' ) echo 'checked'; ?>>
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

    function custom_save_developer_metabox($post_id){

        // check autosave
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // these values will only be updated if the current user is a Developer
        if( !user_is_developer() ) return;

        if( isset($_POST['custom_developer_id']) ) {
            update_post_meta($post_id, 'custom_developer_id', $_POST['custom_developer_id']);
        }
        if( isset($_POST['_custom_lock']) ) {
            $value = $_POST['_custom_lock'] == 'on' ? 'on' : 0;
            update_post_meta($post_id, '_custom_lock', $value);
        } else {
            update_post_meta($post_id, '_custom_lock', 0);
        }

        if( isset($_POST['_custom_hide_richedit']) ){
            $value = $_POST['_custom_hide_richedit'] == 'on' ? 'on' : 0;
            update_post_meta($post_id, '_custom_hide_richedit', $_POST['_custom_hide_richedit']);
        } else {
            update_post_meta($post_id, '_custom_hide_richedit', 0);
        }

    }
    add_action('save_post', 'custom_save_developer_metabox');

    function user_is_developer(){
        $roles = wp_get_current_user()->roles;
        return in_array( 'developer', $roles );
    }

    // Gets page by a given dev ID
    function get_page_by_dev_id( $dev_id ){
        $args = array(
            'posts_per_page'   => 1,
            'meta_key'         => 'custom_developer_id',
            'meta_value'       => $dev_id,
            'post_type'        => 'page',
        );
        return reset( get_posts($args) );
    }

    // Convenience function - get relative path by dev ID
    function path_from_dev_id($dev_id, $after = ''){
        $retrieved_page = get_page_by_dev_id($dev_id);

        if( !$retrieved_page ){
            return '#404';
        }

        $base_url = rtrim(wp_make_link_relative(get_permalink($retrieved_page)), '/');
        return $base_url . $after;
    }

    // Convenience function - get slug by dev ID
    function slug_from_dev_id($dev_id){
        $retrieved_page = get_page_by_dev_id($dev_id);

        if( !$retrieved_page ){
            return '';
        }

        return $retrieved_page->post_name;
    }

    // Gets the nth child of a page with a given Developer ID
    function get_child_of_dev_id($dev_id, $nth_child = 0){
        $parent = get_page_by_dev_id($dev_id);

        $args = array(
            'posts_per_page'   => 1,
            'offset'           => $nth_child,
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'post_type'        => 'page',
            'post_parent'      => $parent->ID,
        );
        return reset(get_posts($args));
    }

    // Gets the relative path of the nth child of a page with given Developer ID
    function get_child_of_dev_id_path($dev_id, $nth_child = 0, $after = ''){
        $permalink = get_permalink(get_child_of_dev_id($dev_id, $nth_child));
        return $base_url = rtrim($permalink, '/');
    }

    // Gets the children of a page with the given Developer ID
    function get_children_of_dev_id($dev_id){
        $parent = get_page_by_dev_id($dev_id);

        if( $parent === false ) {
            return false;
        }

        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'page',
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'post_parent'      => $parent->ID,
        );
        return get_posts($args);
    }

    // Makes sure Developer role can sort Nested Pages automatically
    function give_developer_ordering_permissions(){

        if( is_plugin_active('wp-nested-pages/nestedpages.php') ){

            $allowed_to_sort = get_option('nestedpages_allowsorting');

            if( !$allowed_to_sort ){
                $allowed_to_sort = array();
            }

            if( !in_array('developer', $allowed_to_sort) ){
                $allowed_to_sort[] = 'developer';
                update_option('nestedpages_allowsorting', $allowed_to_sort);
            }
        }

    }
    add_action('admin_init', 'give_developer_ordering_permissions', 1);

    // add 'is-developer' class to WP admin pages if we're a developer
    function add_developer_admin_body_class($classes){
        if( user_is_developer() ){
            $classes .= 'is-developer';
        }
        return $classes;
    }
    add_filter('admin_body_class', 'add_developer_admin_body_class');

    // add extra capabilities for common plugins
    function add_extra_vh_capabilities(){
        global $wp_roles;

        // add desired developer capabilities here
        $caps_to_add = array(
            'manage_woocommerce',
            'view_woocommerce_reports'
        );

        // woocommerce defaults
        // (https://github.com/woocommerce/woocommerce/blob/master/includes/class-wc-install.php#L981)
        $capability_types = array( 'product', 'shop_order', 'shop_coupon' );
        foreach ( $capability_types as $capability_type ) {
            $wc_caps_to_add = array(
                // Post type.
                "edit_{$capability_type}",
                "read_{$capability_type}",
                "delete_{$capability_type}",
                "edit_{$capability_type}s",
                "edit_others_{$capability_type}s",
                "publish_{$capability_type}s",
                "read_private_{$capability_type}s",
                "delete_{$capability_type}s",
                "delete_private_{$capability_type}s",
                "delete_published_{$capability_type}s",
                "delete_others_{$capability_type}s",
                "edit_private_{$capability_type}s",
                "edit_published_{$capability_type}s",
                // Terms.
                "manage_{$capability_type}_terms",
                "edit_{$capability_type}_terms",
                "delete_{$capability_type}_terms",
                "assign_{$capability_type}_terms",
            );

            foreach($wc_caps_to_add as $wc_cap_to_add){
                $caps_to_add[] = $wc_cap_to_add;
            }
        }

        // get developer role
        $developer_role = get_role('developer');

        // add desired caps to developer if they don't exist yet
        foreach($caps_to_add as $cap_to_add){
            if( !$developer_role->has_cap($cap_to_add) ){
                $wp_roles->add_cap( 'developer', $cap_to_add );
            }
        }
    }
    // add permissions after any plugin activated
    add_action('activated_plugin', 'add_extra_vh_capabilities');
