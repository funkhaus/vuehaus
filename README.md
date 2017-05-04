## What
Vuepress is a theme used to build smooth, responsive [Wordpress](https://wordpress.org/) sites with [Vue.js](https://vuejs.org/).

## Install
1. Clone or download this repo.
1. `npm install`
1. `npm run dev`

## Development
### Customizing States, Queries, and Serializers
First, some Vuepress definitions:

* A **state** is a species of page.
    * Example: 'front-page', 'work-grid', or 'single' are all page states.
* A **query** is a server-side request for a particular page or pages.
    * Example: The 'work-grid' state may run a query for all the child pages of the 'work-grid' page.
* A **serializer** is a function that accepts a single `WP_Post` or post ID and returns an array of relevant information from that post.
    * Example: Each child page retrieved with the `work-grid` query will be run through a serializer that returns that child's title and metadata.

### State/Query Rules
* Define new states in the `get_conditional_state` function in `functions.php`.
* Once you've defined a new state, you can either:
    * Create a new query with the same name as the state in the `queries/` folder, which Vuepress will automatically run on that state, **OR**
    * Don't create a new query for the state, falling back to `queries/default.php`.
* Each query **must** contain an array called `$data`, which will be converted to JSON when the page loads. The contents of `$data` are up to the developer.

## Under the Hood
Vuepress handles Wordpress pages a little different than normal.

When you request any Vuepress page using the header `CONTENT_TYPE = application/json` or the query `?content=json`, you'll get a JSON dump of the data for that particular page. (Go ahead, try it - add `?content=json` at the end of the URL on any Vuepress site and see what comes back.)

Requesting a page without that header or query, though, runs the following process:

1. Every page on a Vuepress site uses `index.html`, which contains some boilerplate code and a single `#app` div for Vue.
1. `functions.php` contains a function called `custom_scripts` that runs on every page and does two things:
    * Loads the `static/bundle.js` file, containing the front-end JS
    * Dumps the target page's JSON onto the page using `wp_localize_script`

Any page on Vuepress therefore has access to the full `bundle.js` script, containing all the Webpacked files from `src/`, as well as the initial data for the landing page, rendered using `wp_localize_script`. This is enough to access the entire site from that single page, as any subsequent page loads will simply access the `?content=json` version of a page and render it out with the `bundle.js` code.

## Todo
* Handle instant state changing/URL lag
