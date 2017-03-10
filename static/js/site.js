var site = {
    init: function() {



    },

    onResize: function(){
        site.winHeight = jQuery(window).height();
        site.winWidth = jQuery(window).width();
    },

    replaceSVGs: function(){

        if( jQuery('#content').hasClass('contact') ) {

            // Load email icons
            jQuery('a.email').each(function(){
                jQuery(this).prepend('<img class="svg" src="'+site.themeURL+'/images/icon-email.svg" /> ');
            });

            // Load map icons
            jQuery('a.map').each(function(){
                jQuery(this).prepend('<img class="svg" src="'+site.themeURL+'/images/icon-map.svg" /> ');
            });
        }

    },

    initFitImages: function(){
        // Create responsive image containers for all entry images.
        // Ignores images marked as .no-img-wrap
        jQuery('.entry > p > img:not(.no-img-wrap)').each(function() {
            var $el = jQuery(this);
            // Set max width to image native width
            var maxWidth = $el.attr('width');

            // Get image aspect ratio
            var ratio = ($el.attr('height') / $el.attr('width')) * 100;
            var style = 'padding-bottom: ' + ratio + '%;';
            $el.
                // Unwrap from <p> tags
                unwrap()
                // Wrap in appropriate divs
                .wrap('<div class="fluid-width-image-wrapper" style="max-width: '+ maxWidth +'px;"><div class="responsive-container" style="' + style + '"></div></div>');
        });
    }

};
jQuery(document).ready(function($){

    site.init();
    // jQuery(window).resize(function(){
    //     window.requestAnimationFrame( site.onResize );
    // });
});
