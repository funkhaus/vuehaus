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