/* global jsonData, _get */

import VueRouter from 'vue-router'
import store from 'src/utils/store'
import Vue from 'vue'

Vue.use( VueRouter )

// load all templates in folder
const templates = require.context('src/templates', true)

// build routing table from jsonData (Rest-Easy data)
const routeTable = []
Object.keys(jsonData.routes).forEach(path => {

    let routeObject = jsonData.routes[path]

    // handle shortcut route delcaration
    // (ie, '/path' => 'MyTemplate' in functions.router.php)
    if( typeof routeObject === 'string' ){
        routeObject = {
            component: routeObject
        }
    }

    // save the path and component name
    routeObject.path = path
    routeObject.name = routeObject.name || routeObject.component

    // get specified component, fallback to default
    let component = templates(`./Default.vue`)
    if ( templates.keys().indexOf(`./${ routeObject.component }.vue`) > -1 ){
        component = templates(`./${ routeObject.component }.vue`)
    }

    // set component
    routeObject.component = component

    // push new route entry to table
    routeTable.push(routeObject)
})

// these two values will determine where we'll scroll on our new page
let targetScroll = null
let savedScroll = null

const router = new VueRouter({
    mode: 'history',
    routes: routeTable,
    scrollBehavior() {
        return targetScroll || { x: 0, y: 0 }
    }
})

router.beforeEach((to, from, next) => {
    // Check to see if we're going from page A to B to A again
    const firstPagePath = _get(store, 'state.referral.path', '')
    const toPath = _get(to, 'path', '')

    if (firstPagePath && toPath && firstPagePath == toPath) {
        // if we are, make our target scroll the position we saved from the first time on A
        targetScroll = savedScroll
    } else {
        // if we're not, default to the top of the page
        targetScroll = { x: 0, y: 0 }
    }

    // Save scroll from previous page
    savedScroll = { x: window.scrollX, y: window.scrollY }

    // Replace query data
    if (to.path !== from.path) {
        store.dispatch('LOAD_AND_REPLACE_QUERYDATA', { path: to.path })
    }

    // Update referral route
    if (from.name !== null) {
        store.commit('UPDATE_REFERRAL_ROUTE', from)
    }
    next()
})

export default router
