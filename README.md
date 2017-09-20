## What
Vuepress is a theme used to build smooth, responsive [Wordpress](https://wordpress.org/) sites with [Vue.js](https://vuejs.org/).

## Install
1. `git clone https://github.com/funkhaus/vuepress my-theme`
1. `cd my-theme`
1. `npm install`
1. `npm run dev`

## Building a Vuepress Site
### Template selection
In Vuepress, you'll be building individual pages with Vue templates instead of PHP templates. This can take some getting used to, but ultimately allows for all of the flexibility and power of Vue right from the start.

How does Vuepress pick the correct Vue template to render for a given page? That template is based on the page's URL, with a list of URLs and Vue template names in `queries/index.php`'s `build_routes` function.

For example, if you wanted to build a front page and an About page, you could set up the following in `build_routes`:

```php
// Don't do it like this! See below for more
array(
    ''              => 'FrontPage',
    '/about'        => 'About'
    // The key is the relative path that a page must match...
    // ...while the value is the Vue template name
);
```

That'd work just fine as long as the user never needed to change the URL to the About page - but what if they wanted to switch it to `our-team`?

### The Developer role and GUIDs
Since URLs can easily change in the Wordpress backend, Vuepress includes a new WP role, Developer, that has access to a set of controls that other roles (even Administrator) can't see. One of these controls is for a page's "GUID" - an arbitrary value that can reliably identify a page.

If we set the About page's GUID to `about`, then rewrite the relevant line in `build_routes` like the following:

```php
...
    '/' . get_page_by_guid('about')->post_name        => 'About'
    // get_page_by_guid is a helper function built into Vuepress
...
```

This will guarantee that the path will always render the About template, even if the user changes the path later on.

### Preventing deletion
Any missing page in the `build_routes` function (for example, if `get_page_by_guid('about')` didn't find any pages) would break the router entirely; a Developer can lock pages to prevent this type of bug. Simply check the "Prevent non-dev deletion" box in the Developer Meta screen to prevent other users from placing that page in the Trash accidentally.

### Example workflow
1. Plan and document the general structure of the site in your README.md. Example:
```
* Front page
    * Work block
    * ...
* About
    * Our History
    * Our Employees
        * Employee Bio
        * ...
```
1. Figure out which pages are necessary to the structure of the site. Give those pages GUIDs and prevent non-Dev deletion. Example:
```
Front Page, About, and Our Employees all have child pages, so we'll give them GUIDs of 'front-page', 'about', and 'our-employees', respectively, as well as lock them.
```
1. Create conditions in `build_routes`:
```
$about_slug = get_page_by_guid('about')->post_name;
$employees_slug = get_page_by_guid('our-employees')->post_name;

array(
    ''                                      => 'FrontPage',
    '/' . $about_slug                       => 'About',

    // You can use wildcards
    '/*/' . $employees_slug                 => 'EmployeesGrid',

    // You can also use variable names with a colon
    '/' . $about_slug . '/:child            => 'GenericAboutChild',

    // And you can use both!
    '/*/' . $employees_slug . '/:employee'  => 'EmployeeDetail'
);
```
1. Create the necessary Vue templates. Example:
```
We defined 'FrontPage', 'About', 'GenericAboutChild', 'EmployeesGrid', and 'EmployeeDetail' above, so we'll be creating each of those as a .vue file in src/components/templates/.
```
1. `npm run dev` and start making your theme!

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
