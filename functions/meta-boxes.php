<?php
/*
 * Add custom metaboxes to the new/edit dashboard page
 */
    function custom_add_metaboxes($post_type, $post){
		add_meta_box('custom_media_meta', 'Media Meta', 'custom_media_meta', 'page', 'normal', 'low');
		//add_meta_box('custom_second_featured_image', 'Second Featured Image', 'custom_second_featured_image', 'page', 'side', 'low');
    }
	add_action('add_meta_boxes', 'custom_add_metaboxes', 10, 2);

/*
 * Build media meta box
 */
	function custom_media_meta($post) {

        // From functions/custom-vuehaus-templates.php
        $custom_vuehaus_templates = get_custom_vuehaus_templates();

		?>

        	<div class="custom-meta">

                <?php if( !empty($custom_vuehaus_templates) and count($custom_vuehaus_templates) > 1 ) : ?>

                    <label for="custom-vuehaus-template">Select the custom template for this page:</label>
                    <select id="custom-vuehaus-template" name="custom_vuehaus_template">
                        <?php foreach( $custom_vuehaus_templates as $template ) : ?>
                            <option value="<?php echo $template; ?>" <?php selected($post->custom_vuehaus_template, $template); ?>><?php echo $template; ?></option>
                        <?php endforeach; ?>
    				</select>
    				<br/>

                <?php endif; ?>

				<label for="video-url">Enter the video URL for this page:</label>
				<input id="video-url" class="short" title="This is needed for all video pages" name="custom_video_url" type="text" value="<?php echo $post->custom_video_url; ?>">
				<br/>
        	</div>

		<?php
	}


/*
 * Second featured image uploader.
 * Requires changes to static/admin.js and to turn on admin enqueue script in wp-functions.php
 * @see: https://codex.wordpress.org/Javascript_Reference/wp.media
 */
    function custom_second_featured_image($post){

        // Meta key (need to update the save_metabox function below to reflect this too!)
        $meta_key = 'second_post_thumbnail';

        // Get WordPress' media upload URL
        $upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );

        // See if there's a media id already saved as post meta
        $image_id = get_post_meta( $post->ID, $meta_key, true );

        // Get the image src
        $image_src = wp_get_attachment_image_src( $image_id, 'post-thumbnail' );

        // For convenience, see if the array is valid
        $has_image = is_array( $image_src );

        ?>

            <div class="custom-meta custom-image-uploader">

                <!-- A hidden input to set and post the chosen image id -->
                <input class="custom-image-id" name="<?php echo $meta_key; ?>" type="hidden" value="<?php echo $image_id; ?>" />

                <!-- Image container, which is manipulated with js -->
                <div class="custom-image-container">
                    <?php if ( $has_image ) : ?>
                        <img src="<?php echo $image_src[0] ?>"/>
                    <?php endif; ?>
                </div>

                <!-- Add & remove image links -->
                <p class="hide-if-no-js">
                    <a class="upload-custom-image <?php if ( $has_image  ) { echo 'hidden'; } ?>" href="<?php echo $upload_link ?>">
                        <?php _e('Set second featured image') ?>
                    </a>
                    <a class="delete-custom-image <?php if ( ! $has_image  ) { echo 'hidden'; } ?>" href="#">
                        <?php _e('Remove image') ?>
                    </a>
                </p>

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
        if( isset($_POST['custom_vuehaus_template']) ) {
	        update_post_meta($post_id, 'custom_vuehaus_template', $_POST['custom_vuehaus_template']);
        }
        if( isset($_POST['custom_video_url']) ) {
	        update_post_meta($post_id, 'custom_video_url', $_POST['custom_video_url']);
        }
        // if( isset($_POST['second_post_thumbnail']) ) {
	    //     update_post_meta($post_id, 'second_post_thumbnail', $_POST['second_post_thumbnail']);
        // }

    }
    add_action('save_post', 'custom_save_metabox');


/*
 * Add video URL metafield on attachments
 */
    function custom_add_attachment_video_url( $fields, $post ) {
        $meta = get_post_meta( $post->ID, 'custom_video_url', true );
        $fields['custom_video_url'] = array(
             'label' =>  __( 'Video URL', 'text-domain' ),
             'input' => 'text',
             'value' => $meta,
             'show_in_edit' => true,
        );

        return $fields;
    }
    add_filter( 'attachment_fields_to_edit', 'custom_add_attachment_video_url', 10, 2 );


/*
 * Update media custom field when updating attachment
 */
    function custom_update_attachment_video_url( $post_id = null ) {
        if( empty($post_id) and isset($_POST['id']) ) {
            $post_id = $_POST['id'];
        }

        if( isset( $_POST['attachments'][ $post_id ]['custom_video_url'] ) ) {
            update_post_meta( $post_id , 'custom_video_url', $_POST['attachments'][ $post_id ]['custom_video_url'] );
        }

        clean_post_cache($post_id);
        return;
    }
    add_action( 'edit_attachment', 'custom_update_attachment_video_url', 1 );
    add_action( 'wp_ajax_save-attachment-compat', 'custom_update_attachment_video_url', 0, 1 );
