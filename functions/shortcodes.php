<?php
/*
 * Translate shortcode to Vue components.
 * Use a new copy of this function for each shortcode supported in wp-content.
 */
	function custom_shortcode_function( $atts ) {

		// Include default props here
        extract(shortcode_atts(array(
			// example:
			'title'         => ''
        ), $atts));

		// Props to pass to Vue component
		$props = 'title="' . $title . '"';

		return '<vue-component-name ' . $props . '/>';
	}
	//add_shortcode( 'shortcode-name', 'custom_shortcode_function' );
