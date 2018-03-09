<?php
/*
 * Translate shortcode to Vue components.
 * Use a new copy of this function for each shortcode supported in wp-content.
 * See the Readme.md file in this directory for more examples.
 *
 * @see https://codex.wordpress.org/Function_Reference/add_shortcode
 */
	function custom_shortcode_function( $atts, $content = '', $name ) {

		// Include default props here
        extract(shortcode_atts(array(
			'title'         => ''
        ), $atts));

		// Props to pass to Vue component
        $props = 'title="' . $title . '"';
        $content = custom_filter_shortcode_text($content);

		return '<vue-component-name ' . $props . '>'. $content .'</vue-component-name>';
	}
	//add_shortcode( 'shortcode-name', 'custom_shortcode_function' );


/**
 * Utility function to clean up the way WordPress auto formats text in a shortcode.
 *
 * @param string $text A string of HTML text
 */
    function custom_filter_shortcode_text($text = '') {
        // Remove all the poorly formatted P tags that WP adds by default.
        $tags = array("<p>", "</p>", "<br>", "</br>");
        $text = str_replace($tags, "", $text);

        // Add back in the P and BR tags again, this will remove empty ones
        return apply_filters('the_content', $text);
    }
