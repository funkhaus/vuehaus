## What
Vuepress is a boilerplate used to build smooth, responsive [Wordpress](https://wordpress.org/) templates with [Vue.js](https://vuejs.org/).

## Install
1. `git clone https://github.com/funkhaus/vuepress my-theme`
1. `cd my-theme`
1. `npm install`
1. Install and activate [Rest-Easy](https://github.com/funkhaus/Rest-Easy)
1. `npm run dev`

## Building a Vuepress Site
Vuepress requires the [Rest-Easy](https://github.com/funkhaus/Rest-Easy) plugin to work correctly, so make sure you have that installed before getting started.

In Vuepress, you'll be building individual pages with Vue instead of PHP templates. This can take some getting used to, but ultimately allows for all of the flexibility and power of Vue right from the start.

Any page on a Vuepress site will use the `index.php` template, which contains some automatically generated header tags and a single div called `#app`. This is where the main Vue component lives, with its content controlled by the [Vue router](https://router.vuejs.org/en/).

### Router and Templates
A Vuepress site's routing table is built at runtime by `functions.php`'s [`add_routes_to_json`](https://github.com/funkhaus/vuepress/blob/master/functions.php#L6-L27) function.

```php
jsonData['routes'] = array(
    // The key is the relative path that a page must match, while the value is the Vue template name
    ''                                  => 'FrontPage',
    '/path'                             => 'VueComponent',
    '/path/:var'                        => 'ComponentWithVar',
    '/path/*/:var'                      => 'WildcardAndVar',
    '/' . get_page_by_guid('your-guid')->post_name  => 'DefinedByGuid'
);
```

Building the routing table dynamically lets the Vue router treat the Wordpress database as the source of truth, ensuring pages route as expected.

For example, if you wanted to build a front page and an About page, you might set up the following in `add_routes_to_json`:

```php
// Don't do it like this! See below for more
jsonData['routes'] = array(
    // The key is the relative path that a page must match, while the value is the Vue template name
    ''              => 'FrontPage',
    '/about'        => 'About'
);
```

That'd work just fine as long as the user never needed to change the URL to the About page - but what if they wanted to switch it to `our-team`?

### The Developer role and GUIDs
Since URLs can easily change in the Wordpress backend, Vuepress includes a new WP role, Developer, that has access to a set of controls that other roles (even Administrator) can't see. One of these controls is for a page's "GUID" - an arbitrary value that can reliably identify a page.

If we set the About page's GUID to `about`, then rewrite the relevant line in `add_routes_to_json` like the following:

```php
...
    // get_page_by_guid is a helper function built into Vuepress
    '/' . get_page_by_guid('about')->post_name        => 'About'
...
```

This will guarantee that the path to this page will always render the About template, even if the user changes that path later on.

### Preventing deletion
Any missing page in the `add_routes_to_json` function (for example, if `get_page_by_guid('about')` didn't find any pages) would break the given route; a Developer can lock pages to prevent this type of bug. Check the "Prevent non-dev deletion" box in the Developer Meta screen to prevent other users from placing that page in the Trash accidentally.



### Example workflow
1. Plan and document the general structure of the site in your README.md. Example:
```
* Front page
* About
    * Our History
    * Our Employees
        * Employee Bio
        * ...
```
2. Figure out which pages are necessary to the structure of the site. Give those pages GUIDs and prevent non-Dev deletion. Example:

> Front Page, About, and Our Employees all have child pages, so we'll give them GUIDs of 'front-page', 'about', and 'our-employees', respectively, as well as lock them.

3. Create conditions in `add_routes_to_json`:
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
4. Create the necessary Vue templates. Example:
```
We defined 'FrontPage', 'About', 'GenericAboutChild', 'EmployeesGrid', and 'EmployeeDetail' above,
so we'll be creating each of those as a .vue file in src/components/templates/.
```
5. `npm run dev` and start building in Vue!

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
