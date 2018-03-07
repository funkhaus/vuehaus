## Example Rest-Easy Filters
Here are a bunch of useful examples of how to use the Rest-Easy filter functions to send custom data to the frontend.


```php
/**
 *  Include the second post thumbnail in all posts
 *
 * @param array $post_data The post currently being processed by Rest-Easy
 */
    function add_second_featured_image($post_data){
        $image_id = get_post_meta($post_data['id'], 'second_post_thumbnail', true);

        if($image_id) {
            $post_data['secondPostThumbnail'] = apply_filters('rez_serialize_attachment', get_post($image_id));
        }
        return $post_data;
    }
    add_filter('rez_serialize_post', 'add_second_featured_image');

```

```php
/**
 * Add a custom formatted date to all posts
 *
 * @param array $post_data The post currently being processed by Rest-Easy
 */
    function custom_format_date($post_data){
        $post = get_post($post_data['id']);
        $post_data['dateFormatted'] = get_the_date('F j Y', $post);
        return $post_data;
    }
    add_filter('rez_serialize_post', 'custom_format_date');
```

```php
/**
 * Add custom credits to each post. Add line breaks while doing it.
 *
 * @param array $post_data The post currently being processed by Rest-Easy
 */
    function custom_format_credits($post_data){
        $post = get_post($post_data['id']);
        $post_data['meta']['customCredits'] = nl2br($post->custom_credits);
        return $post_data;
    }
    add_filter('rez_serialize_post', 'custom_format_credits');
```


```php
/**
 * Overwrite the loop when in a tree to always be the children of the parent page.
 * This is useful when you want a child page to actually return all siblings as the main loop.
 * Common for contact pages that show as an accordian menu.
 *
 * @param array $loop All the pages/posts in the cuurent loop
 */
    function serialize_contact_pages($loop){
        
        $page = get_page_by_dev_id('contact');
        
        if( is_tree($page->ID) ) {
            $loop = array();
            $loop[0] = apply_filters('rez_serialize_object', get_post($page->ID));
            $loop[0]['related'] = apply_filters('rez_gather_related', get_post($page->ID));
        }
        return $loop;
    }
    add_filter('rez_build_loop_data', 'serialize_contact_pages');
    
```


## Example of some useful shortcodes
Here are a bunch of useful examples of how to use WordPress shortcodes with Vuepress. You will need both the PHP and the Vue template.


```php
/*
 * Image component (left or right aligned), with text on either side of it.
 */
	function custom_shortcode_image( $atts, $content = '', $name) {
        $props = '';
        $content = custom_filter_shortcode_text($content);

		// Include default props here
        extract(shortcode_atts(array(
			'id'         => '' // ID of WordPress attachment/image
        ), $atts));

        if( !empty($id) ) {
            $image = apply_filters('rez_serialize_attachment', get_post($id));

            // Props to pass to Vue component
    		$props = ':image="' . esc_attr(json_encode($image)) . '"';
        }

		return '<image-shortcode '. $props .' name="'. $name .'">'. $content .'</image-shortcode>';
	}
    add_shortcode( 'image-left', 'custom_shortcode_image' );
    add_shortcode( 'image-right', 'custom_shortcode_image' );
```

```vue
<template>

    <div :class="classes">
        <div v-if="image" class="image">
            <responsive-image :object="image"/>
        </div>
        <div v-if="$slots.default" class="text">
            <slot></slot>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            image: {
                type: Object,
                default: ()=>{}
            },
            name: {
                type: String,
                default: 'image-left'
            }
        },
        computed: {
            classes () {
                return [
                    'shortcode',
                    'image-shortcode',
                    this.name
                ]
            }
        }
    }
</script>

<style lang="scss">
    @import 'src/styles/vars';

    .image-shortcode {
        margin: 50px auto;

        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
        align-content: center;
        align-items: center;

        .image {
            width: 50%;
            padding-right: 30px;
            box-sizing: border-box;
        }
        .text {
            width: 50%;
            padding: 0 40px;
            box-sizing: border-box;
            margin: 0 auto;
        }
    }
    .image-shortcode.image-right {
        .image {
            padding-left: 30px;
            padding-right: 0;
            order: 1;
        }
        .text {
            order: 0;
        }
    }
</style>
```



```php
/*
 * Half column of text
 */
	function custom_shortcode_half_column( $atts, $content = '', $name ) {
        $content = custom_filter_shortcode_text($content);
        return '<half-column>'. $content .'</half-column>';
	}
	add_shortcode( 'half-column', 'custom_shortcode_half_column' );
```	

```vue
<template>

    <div class="shortcode half-column">
        <slot></slot>
    </div>

</template>

<script>
    export default {}
</script>

<style lang="scss">
    @import 'src/styles/vars';

    .half-column {
        width: 50%;
        display: inline-block;
        vertical-align: top;
    }
    .half-column + .half-column {
        width: 45%;
    }
</style>
```