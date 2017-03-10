import $ from 'jquery'

export default function(prop){

    // init global cache object and assign local var
    let cache = this.svgCache = this.svgCache || {}

    // Set total and counter
    let $svgs = $('img.svg')
    let total = $svgs.length
    let count = 0

    // If no SVGs on page, fire callback event
    if ( total === count ){
        $(document).trigger('svgsLoaded', [count])
        return
    }

    // define function to replace single svg
    let replaceSVG = function( data ){

        // get img and attributes
        let $img = $(this),
            attributes = $img.prop('attributes')

		// Increment counter
        count++

        // Clone the SVG tag, ignore the rest
        let $svg = $(data).find('svg').clone()

        // Remove any invalid XML tags as per http://validator.w3.org
        $svg = $svg.removeAttr('xmlns:a')

        // Loop through IMG attributes and add them to SVG
        $.each(attributes, function() {
            $svg.attr(this.name, this.value)
        })

        // Replace image with new SVG
        $img.replaceWith($svg)

		// If this is the last svg, fire callback event
        if ( total === count ) $(document).trigger('svgsLoaded', [count])

    }

    // loop all svgs
    $svgs.each(function(){

        // get URL from this SVG
        let imgURL = $(this).attr('src')

        // if not cached, make new AJAX request
        if ( ! cache[imgURL] ){
            cache[imgURL] = $.get(imgURL).promise()
        }

        // when we have SVG data, replace img with data
        cache[imgURL].done( replaceSVG.bind(this) )

    })
}
