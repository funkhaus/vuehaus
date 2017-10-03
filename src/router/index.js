/* global jsonData */

import VueRouter from 'vue-router'
import store from 'src/store'
import Vue from 'vue'

Vue.use( VueRouter )

// load all templates in folder
const templates = require.context('src/components/templates', true)

// build routing table
const routeTable = []
Object.keys(jsonData.routes).forEach(path => {

    const cmpName = jsonData.routes[path]

    if ( cmpName == 'REDIRECT_HOME' ){
        routeTable.push({ path, redirect: '/' })
        return true
    }

    // get specified component, fallback to default
    let component = templates(`./default.vue`)
    if ( templates.keys().indexOf(`./${ cmpName }.vue`) > -1 ){
        component = templates(`./${ cmpName }.vue`)
    }

    // push new route entry to table
    routeTable.push({ component, path, name: cmpName })
})

const router = new VueRouter( {
    mode: 'history',
    routes: routeTable,
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
})

router.beforeEach(( to, from, next ) => {
    if( to.path !== from.path ){
        store.dispatch( 'LOAD_AND_REPLACE_QUERYDATA', { path: to.path } )
    }
    next()
})

export default router
