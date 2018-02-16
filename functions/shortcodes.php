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

/*
 * [svg-image] shortcode
 */
	function add_svg_image_shortcode( $atts ) {

        extract(shortcode_atts(array(
			'src'         => ''
        ), $atts));

		$props = 'src="' . $src . '"';

		return '<svg-image ' . $props . '/>';
	}
	add_shortcode( 'svg-image', 'add_svg_image_shortcode' );
