<?php
/*
 * Add custom metaboxes to the new/edit dashboard page
 */
    function custom_add_metaboxes($post_type, $post){

        // From functions/custom-vuepress-templates.php
        $custom_vuepress_templates = get_custom_vuepress_templates();

        if( !empty($custom_vuepress_templates) and count($custom_vuepress_templates) > 1 ){
            add_meta_box('custom_vuepress_template', 'Vuepress Template', 'custom_vuepress_template', 'page', 'normal', 'low');
        }
    }
	add_action('add_meta_boxes', 'custom_add_metaboxes', 10, 2);

/*
 * Build media meta box
 */
	function custom_vuepress_template($post) {

        // From functions/custom-vuepress-templates.php
        $custom_vuepress_templates = get_custom_vuepress_templates();

        ?>

        	<div class="custom-meta">

                <label for="custom-vuepress-template">Select the custom template for this page:</label>
                <select id="custom-vuepress-template" name="custom_vuepress_template">
                    <?php foreach( $custom_vuepress_templates as $template ) : ?>
                        <option value="<?php echo $template; ?>" <?php selected($post->custom_vuepress_template, $template); ?>><?php echo $template; ?></option>
                    <?php endforeach; ?>
				</select>
				<br/>

        	</div>

        <?php

	}

/*
 * Save metabox values
 */
    function custom_save_metabox($post_id){

        // check autosave
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }
        if( isset($_POST['custom_vuepress_template']) ) {
	        update_post_meta($post_id, 'custom_vuepress_template', $_POST['custom_vuepress_template']);
        }
    }
    add_action('save_post', 'custom_save_metabox');
