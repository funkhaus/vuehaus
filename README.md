## What
Vuepress is a boilerplate used to build smooth, responsive [WordPress](https://wordpress.org/) templates with [Vue.js](https://vuejs.org/).

## Table of Contents
1. [Install](#install)
1. [Reading List](#reading-list)
1. [Building a Vuepress Site: Back-End](#building-a-vuepress-site-back-end)
    1. [Router and Templates](#router-and-templates)
    1. [The Developer Role and Developer IDs](#the-developer-role-and-developer-ids)
    1. [Developer Capabilities](#developer-capabilities)
    1. [Advanced Routing](#advanced-routing)
    1. [Utility Functions](#utility-functions)
    1. [Upgrading Plugins](#upgrading-plugins)
1. [Vuex and State](#vuex-and-state)
    1. [Mutations](#mutations)
    1. [Actions](#actions)
1. [Building a Vuepress Site: Front-End](#building-a-vuepress-site-front-end)
    1. [Example Workflow](#example-workflow)
    1. [Vuepress Events](#vuepress-events)
    1. [Common Tasks](#common-tasks)
1. [Testing](#testing)
    1. [Test Configuration](#test-configuration)
    1. [Alternate Test Files](#alternate-test-files)
1. [Recommended Reading](#recommended-reading)



## Install
1. `git clone https://github.com/funkhaus/vuepress my-theme`
1. `cd my-theme`
1. `npm install`
1. Go to the WordPress back-end, activate, the Vuepress theme, and follow the instructions to install [Rest-Easy](https://github.com/funkhaus/Rest-Easy).
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
Vuepress requires the [Rest-Easy](https://github.com/funkhaus/Rest-Easy) plugin to work correctly, so make sure you have that installed before getting started. Vuepress ships with [TGM Plugin Activation](http://tgmpluginactivation.com/) to make Rest-Easy installation simpler.

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
    path_from_dev_id('dev-id')    => 'DefinedByDeveloperId',
    path_from_dev_id('dev-id', '/append-me') => 'DevIdPathPlusAppendedString'
);
```

Building the routing table dynamically lets the Vue router treat the WordPress database as the source of truth, ensuring pages route as expected.

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

### The Developer Role and Developer IDs
Since URLs can easily change in the WordPress backend, Vuepress includes a new WP role, Developer, that has access to a set of controls that other roles (even Administrator) can't see. One of these controls is for a page's "Developer ID" - an arbitrary value that can reliably identify a page.

The Developer ID is accessible via a post object's `custom_developer_id` property - for example, `$post->custom_developer_id`.

If we set the About page's Developer ID to `about`, then rewrite the relevant line in `add_routes_to_json` like the following:

```php
...
    // path_from_dev_id is a Vuepress function that retrieves a page's relative path from its Developer ID
    path_from_dev_id('about')                         => 'About'
...
```

This will guarantee that the path to this page will always render the About template, even if the user changes that path later on.

### Developer Capabilities

The Developer role in Vuepress has a few extra capabilities available:

#### Preventing deletion
Any missing page in the `add_routes_to_json` function (for example, if `get_page_by_dev_id('about')` didn't find any pages) would break the given route; a Developer can lock pages to prevent this type of bug. Check the "Prevent non-dev deletion" box in the Developer Meta screen to prevent other users from placing that page in the Trash accidentally.

#### Hiding the rich editor
Check the "Hide Rich Editor" box to prevent non-Developer users from using WordPress's rich editor. This can be helpful to maintain stricter controls over the template and class names in a page's content.

### Advanced Routing
Take a look at the [path-to-regexp documentation](https://github.com/pillarjs/path-to-regexp) for examples of routing using regex capabilities.

The routing table in Vuepress automatically converts a string-string key-value pair to a Vue route object:

```php
array(
    path_from_dev_id('my-developer-id') => 'MyComponentName'
)
```

...turns to:

```js
const router = new VueRouter({
    routes: [
        { path: '/my-developer-id', component: 'MyComponentName.vue' }
    ]
})
```

You can take advantage of the Vue router's more advanced capabilities, like [redirect/alias](https://router.vuejs.org/en/essentials/redirect-and-alias.html), [naming](https://router.vuejs.org/en/essentials/named-routes.html), and more by setting the value to an object:

```php
array(
    path_from_dev_id('your-developer-id') => array(
        // Redirect to a path - in this case, to the path of the first child
        'redirect'		=> get_child_of_dev_id_path('work')
    ),

    path_from_dev_id('your-developer0id', '/:medium*')		=> array(
        // Define a component and a name for the route
        'component'		=> 'WorkGrid',
        'name'			=> 'work-grid'
    ),

    path_from_dev_id('your-developer-id') => array(
        // Redirect to a named route
        'redirect'		=> array(
            'name':     => 'work-grid'
        )
    )
)
```

This isn't the limit of the routing table's capabilities - anything the Vue router can do, you can build in the `add_routes_to_json` function.

### Utility Functions
Vuepress defines a few utility functions to make building the routing table easier:

* `get_child_of_dev_id($dev_id, $nth_child = 0)` - Get the post object of the nth child (zero-based, default `0`) of a page with the given Developer ID.
* `get_child_of_dev_id_path($dev_id, $nth_child = 0, $after = '')` - Get the relative path of the nth child of a page with the given Developer ID. Adds `$after` to the retrieved path.
* `path_from_dev_id($dev_id, $after = '')` - Get the relative path of a page with a given Developer ID. Adds `$after` to the retrieved path.

### Upgrading Plugins
If you need to upgrade your version of [Rest-Easy](https://github.com/funkhaus/Rest-Easy), change the `$latest_rest_easy` variable in `functions/vuepress-plugins.php` to match the latest Rest Easy version. You'll be prompted to upgrade the next time you load any page on the WordPress backend.

## Vuex and State
Vuepress uses [Vuex](https://vuex.vuejs.org/en/intro.html) to handle a site's state. The default store in `src/store/index.js` is set up like this:

```js
{
    // From Rest-Easy
    site: jsonData.site,
    meta: jsonData.meta,
    loop: jsonData.loop,

    // Vuepress-specific
    transitioning_in: false,
    transitioning_out: false,
    loaded: true
}
```

See the [Rest-Easy documentation](https://github.com/funkhaus/rest-easy) for more information on `jsonData` and its properties, as well as the [Vuex documentation](https://vuex.vuejs.org/en/intro.html) for Vuex terms like store, state, mutation, etc.

### Mutations
You can commit a mutation from any Vue component by using:

```js
this.$store.commit('MUTATION_NAME', payload)
```

Default Vuepress mutations:

* `'REPLACE_QUERYDATA', { site, meta, loop }` - Replaces the `store`'s `site`, `meta`, and `loop` properties with the `site`, `meta`, and `loop` properties of the payload.
* `'SET_TRANSITIONING_IN, true | false'` - Sets `state.transitioning_in` to the given value.
* `'SET_TRANSITIONING_OUT, true | false'` - Sets `state.transitioning_out` to the given value.
* `'SET_LOADED', true | false` - Sets `state.loaded` to the given value.

### Actions
Where mutations are synchronous, actions are asynchronous:

```js
this.$store.dispatch('ACTION_NAME', payload)
```

Default Vuepress actions:

* `'LOAD_AND_REPLACE_QUERYDATA, { path: 'url string' }'` - Runs the following process:
    1. Sets `state.loaded` to `false`.
    1. Checks `src/services/cache.js` (which is a global cache that can be `import`ed into any other file) for the given `path` key.
        If none is found:
        1. Commits `'SET_LOADED', false`
        1. Fetches the data from the the URL at the payload path.
        1. Saves the data to the cache.
    1. Commits `'REPLACE_QUERYDATA'` with the data from the cache.
    1. Commits `'SET_LOADED', true`

## Building a Vuepress Site: Front-End
Once you've set up the routing for a Vuepress site and understand its state functions, you can start working with Vue templates themselves.

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
1. Figure out which pages are necessary to the structure of the site. Give those pages Developer IDs and prevent non-Dev deletion. Example:

    > Front Page, About, and Our Employees all have child pages, so we'll give them Developer IDs of 'front-page', 'about', and 'employees', respectively, as well as lock them.

1. Create conditions in `functions/router.php`'s `add_routes_to_json` function:

    ```
    array(
        ''                                  => 'FrontPage',
        path_from_dev_id('about')             => 'About',
        path_from_dev_id('employees)          => 'EmployeesGrid',
        path_from_dev_id('about', '/:child')  => 'AboutChildGeneric',
        path_from_dev_id('employees', '/:employee') => 'EmployeeDetail'
    );
    ```

1. Create the necessary Vue templates. Example:
    > We defined 'FrontPage', 'About', 'EmployeesGrid', 'AboutChildGeneric', and 'EmployeeDetail' above, so we'll be creating each of those as a .vue file in `src/components/templates/`.

1. `npm run dev` and start building in Vue!

### Vuepress Events
Throttled resize and scroll events are available to any child of the App component:

```js
this.$root.$on('throttled.resize', () => {
    // your throttled resize event here...
    // default: 1 per 10ms
})
this.$root.$on('throttled.scroll', () => {
    // your throttled scroll event here...
    // default: 1 per 10ms
})
```

Both events are fired after the `$root` element saves updated window dimensions/scroll positions for resize/scroll events.

### Common Tasks
* __Loading Fonts:__
    Vuepress includes the Google/Typekit [Web Font Loader](https://github.com/typekit/webfontloader) in `index.php`. Follow the instructions on that repo to load fonts from Google, Typekit, or your own uploads.
* __Using SVGs:__
    In the `script` section of your template:

    ```js
    import svg from 'src/images/example.svg'

    export default {
        data(){
            exampleSvg: svg
        }
    }
    ```

    And in the `template` section:

    ```html
    <div class="svg-wrap" v-html="exampleSvg"></div>
    ```

* __Images:__
    Vuepress comes with a component called `responsive-image` that provides some built-in image handling, including fading in images as they load. You can pass an image object from Rest-Easy's attachment serializer and it will build itself automatically:

    ```html
    <!-- Build a responsive image component from the featured image of the first post in The Loop -->
    <responsive-image :object="$store.state.loop[0].related.featured_attachment"/>
    ```

    You can also include any of the following attributes:

    ```html
    <responsive-image
        src="source-url"
        height="height in px"
        width="width in px"
        aspect="aspect ratio, as percent (ie '56' for 56%)"
        size="WordPress-defined size slug"
        color="background color of pre-loaded space"
        />
    ```

    You must include either an `object` or a `src` parameter on a `responsive-image` element; all other values are optional.

## Testing
Vuepress comes with some commands to simplify testing your site.

1. `npm i --only=dev` - Installs dev dependencies like [Puppeteer](https://github.com/GoogleChrome/puppeteer) and [Rimraf](https://www.npmjs.com/package/rimraf)
1. Navigate to `.testsrc.json` and fill in the desired base URL, then additional steps in the test
1. `npm run test` - Runs all the steps in the selected test file. Outputs to `tests/output-${ timestamp }/`.
1. (Optional) `npm run cleanup` - Removes all test output directories.

### Test Configuration
You can edit the contents of `.testrc.json` to change the `npm run test` configuration.

Features to be implemented appear in

> blockquotes.

* `baseUrl` (string) - Equivalent to WordPress's `get_site_url()`. Base path for all files.
* `itinerary` (array of objects) - The tests to complete.
    * `action` (string) - Action to take:
        * `click` - Clicks on `selector`'s first match and wait for the result to load.
        * `evaluate` - Checks for the following keys and runs them:
            * `function`
            * `expected`
        * `goto` (string) - Goes to a path (relative to `baseUrl`).
        * `hover` (string) - Hovers over the element defined by `selector`.
        * `scroll` - Scrolls to `selector`'s first match or by `number` pixels.
        * > `startScreencast` - Starts a screencast.
        * > `stopScreencast` - Stops a screencast.
    * `expected` (any) - Compare `evaluate`'s output to this value.
    * `function` (string) - Runs a JS function in the context of the page and logs the result.
    * `label` (string) - Label for the test. Also acts as the name of a screenshot taken on this step.
    * `message` (string) - Custom message to output when running the test.
    * `number` (number) - Number to apply to `action`.
    * `path` (string) - Relative path to target in `goto`.
    * `selector` (string) - Target of `action` or `test`.
    * `waitFor` (string) - Waits for an event to continue. Useful when clicking on a link, for example.
        * `load` - Waits for next page to load.

### Alternate Test Files
You can create multiple test files and pick which one to write:

```sh
node tests --config your-file-name.json
node tests -c your-file-name.json
```

## Recommended Reading
Not Vuepress-specific reading material, but rather good practices and articles.

1. [Maintainable CSS](https://maintainablecss.com/chapters/introduction/), a guide to writing readable and easily-maintained CSS
1. [SVG Tips for Designers](https://www.sarasoueidan.com/blog/svg-tips-for-designers/)


--------

__Vuepress__

http://funkhaus.us

Version: 1.1.2

* 1.1.2 - Added [TGM Plugin Activation](http://tgmpluginactivation.com/) to require plugins. Switching to x.x.x version numbering.
* 1.11 - Switched `_custom_developer_id` to `custom_developer_id`
* 1.1 - Switched `_custom_guid` to `_custom_developer_id`
* 1.0 - Initial release
