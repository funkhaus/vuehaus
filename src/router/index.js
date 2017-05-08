/* global queryData */

import Vue from 'vue'
import VueRouter from 'vue-router'
import store from 'src/store'
import _ from 'lodash'

Vue.use( VueRouter )

// load all templates in folder
const templates = require.context('src/components/templates', true)

// build routing table
const routeTable = []
_.each(queryData.shared.routes, (cmpName, path) => {

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
    routes: routeTable
} )

router.beforeEach( ( to, from, next ) => {
    if( to.path !== from.path ){
        store.dispatch( 'LOAD_AND_REPLACE_QUERYDATA', to.path )
    }
    next()
} )

export default router
