## What
Vuepress is a theme used to build smooth, responsive [Wordpress](https://wordpress.org/) sites with [Vue.js](https://vuejs.org/).

## Install
1. `git clone https://github.com/funkhaus/vuepress my-theme`
1. `cd my-theme`
1. `npm install`
1. `npm run dev`

## Building a Vuepress Site
Vuepress sites use a state machine to render individual pages. The recommended workflow is:

1. Plan and document the general structure of the site in your README.md. For example:
```
    * Front page
        * Work block
        * ...
    * Work Grid
        * Work detail
        * ...
    * About
        * Our History
        * Our Employees
            * Employee Bio
            * ...
```
1. Define paths in `queries/index.php`'s `build_routes` function.
    * It's a good idea to reference a page's slug rather than hardcoding it. For this reason, Vuepress comes with a meta field called `_custom_guid` and a convenience function called `get_page_by_guid( $guid )` that you can use to find a page's slug. For example:  
        `'/' . get_page_by_guid( 'director_grid' )->post_name => 'DirectorGrid'`
        You can always use a page's ID, too:
        `'/' . get_post( $your_page_id )->post_name => 'DirectorGrid'`


TODO: Continue

## Development
Vuepress handles Wordpress pages a little different than normal.

When you request any Vuepress page using the header `CONTENT_TYPE = application/json` or the query `?content=json`, you'll get a JSON dump of the data for that particular page. (Go ahead, try it - add `?content=json` at the end of the URL on any Vuepress site and see what comes back.)

Requesting a page without that header or query, though, runs the following process:

1. Every page on a Vuepress site uses `index.html`, which contains some boilerplate code and a single `#app` div for Vue.
1. `functions.php` contains a function called `custom_scripts` that runs on every page and does two things:
    * Loads the `static/bundle.js` file, containing the front-end JS
    * Dumps the target page's JSON onto the page using `wp_localize_script`

Any page on Vuepress therefore has access to the full `bundle.js` script, containing all the Webpacked files from `src/`, as well as the initial data for the landing page, rendered using `wp_localize_script`. This is enough to access the entire site from that single page, as any subsequent page loads will simply access the `?content=json` version of a page and render it out with the `bundle.js` code.

## Notes

TODO: Organize these notes

* **SVG Loading** - run like this: ```import arrowRightSVG from 'src/icons/arrow-right.svg'```

## Todo
* Handle instant state changing/URL lag
