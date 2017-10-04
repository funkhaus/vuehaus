## What
Vuepress is a boilerplate used to build smooth, responsive [Wordpress](https://wordpress.org/) templates with [Vue.js](https://vuejs.org/).

## Install
1. `git clone https://github.com/funkhaus/vuepress my-theme`
1. `cd my-theme`
1. `npm install`
1. Install and activate [Rest-Easy](https://github.com/funkhaus/Rest-Easy)
1. `npm run dev`

## Reading List
To get started with Vuepress, you should already know or familiarize yourself with:
1. [WordPress](https://wordpress.org/)
1. [Node.js and NPM](https://nodejs.org/en/download/) (npm is included with Node)
1. [Vue.js](https://vuejs.org/)

To get the most out of Vuepress, you can continue with:
1. [Rest-Easy](https://github.com/funkhaus/Rest-Easy), a WordPress plugin by Funkhaus
1. The [Vue router](https://router.vuejs.org/en/)
1. [Vuex](https://vuex.vuejs.org/en/intro.html)

## Building a Vuepress Site: Back-End
Vuepress requires the [Rest-Easy](https://github.com/funkhaus/Rest-Easy) plugin to work correctly, so make sure you have that installed before getting started.

In Vuepress, you'll be building individual pages with Vue instead of PHP templates. This can take some getting used to, but ultimately allows for all of the flexibility and power of Vue right from the start.

Any page on a Vuepress site will use the `index.php` template, which contains some automatically generated header tags and a single div called `#app`. This is where the main Vue component lives, with its content controlled by the [Vue router](https://router.vuejs.org/en/).

### Router and Templates
A Vuepress site's routing table is built at runtime by `functions.php`'s [`add_routes_to_json`](https://github.com/funkhaus/vuepress/blob/master/functions.php#L6-L27) function. The table uses the Vue router, which in turn uses [path-to-regexp](https://github.com/pillarjs/path-to-regexp) to parse paths.

```php
jsonData['routes'] = array(
    // The key is the relative path that a page must match, while the value is the Vue template name
    ''                                  => 'FrontPage',
    '/path'                             => 'VueComponent',
    '/path/:var'                        => 'ComponentWithVar',
    '/path/:optional*/:var'             => 'WildcardAndVar',
    path_from_guid('your-guid')         => 'DefinedByGuid',
    path_from_guid('guid', '/append-me') => 'GuidPathPlusAppendedString'
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
    // path_from_guid is a Vuepress function that retrieves a page's relative path from its GUID
    path_from_guid('about')                         => 'About'
...
```

This will guarantee that the path to this page will always render the About template, even if the user changes that path later on.

### Advanced Routing
Take a look at the [path-to-regexp documentation](https://github.com/pillarjs/path-to-regexp) for examples of routing using regex capabilities.

The routing table in Vuepress automatically converts a string-string key-value pair to a Vue route object:

```php
array(
    path_from_guid('my-guid') => 'MyComponentName'
)
```

...turns to:

```js
const router = new VueRouter({
    routes: [
        { path: '/my-guid', component: 'MyComponentName.vue' }
    ]
})
```

You can take advantage of the Vue router's more advanced capabilities, like [redirect/alias](https://router.vuejs.org/en/essentials/redirect-and-alias.html), [naming](https://router.vuejs.org/en/essentials/named-routes.html), and more by setting the value to an object:

```php
array(
    path_from_guid('my-guid') => array(

    )
)
```

### Preventing deletion
Any missing page in the `add_routes_to_json` function (for example, if `get_page_by_guid('about')` didn't find any pages) would break the given route; a Developer can lock pages to prevent this type of bug. Check the "Prevent non-dev deletion" box in the Developer Meta screen to prevent other users from placing that page in the Trash accidentally.

### Vuex & State
Vuepress uses [Vuex](https://vuex.vuejs.org/en/intro.html) to handle a site's state. The default store in `src/store/index.js` is set up like this:

```js
{
    site: jsonData.site,
    meta: jsonData.meta,
    loop: jsonData.loop,
    transitioning_in: false,
    transitioning_out: false
}
```

See the [Rest-Easy documentation](https://github.com/funkhaus/rest-easy) for more information on `jsonData` and its properties, as well as the [Vuex documentation](https://vuex.vuejs.org/en/intro.html) for Vuex terms like store, state, mutation, etc.

#### Mutations
You can commit a mutation from any Vue component by using:

```js
this.$store.commit('MUTATION_NAME', payload)
```

Default Vuepress mutations:

* `'REPLACE_QUERYDATA', { site, meta, loop }` - Replaces the `store`'s `site`, `meta`, and `loop` properties with the `site`, `meta`, and `loop` properties of the payload.
* `'SET_TRANSITIONING_IN, true | false'` - Sets `state.transitioning_in` to the given value.
* `'SET_TRANSITIONING_OUT, true | false'` - Sets `state.transitioning_out` to the given value.

#### Actions
Where mutations are synchronous, actions are asynchronous:

```js
this.$store.dispatch('ACTION_NAME', payload)
```

Default Vuepress actions:

* `'LOAD_AND_REPLACE_QUERYDATA, { path: 'url string' }'` - Fetches the data from the the URL at the payload path. Caches in `src/services/cache.js`, which can be `import`ed into any other file. Commits the `REPLACE_QUERYDATA` mutation with the new data when that data is fetched and cached.

## Building a Vuepress Site: Front-End
Once you've set up the routing for a Vuepress site, you can start working with Vue templates themselves.

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
