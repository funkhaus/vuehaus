/* global jsonData */

import VueRouter from 'vue-router'
import store from 'src/utils/store'
import Vue from 'vue'

Vue.use(VueRouter)

// load all templates in folder
const templates = require.context('src/views', true)

// build routing table
const routeTable = []
Object.keys(jsonData.routes).forEach(path => {
    let routeObject = jsonData.routes[path]

    if (typeof routeObject === 'string') {
        routeObject = {
            component: routeObject
        }
    }

    routeObject.path = path

    routeObject.name = routeObject.name || routeObject.component

    // get specified component, fallback to default
    let component = templates(`./Default.vue`)
    if (templates.keys().indexOf(`./${routeObject.component}.vue`) > -1) {
        component = templates(`./${routeObject.component}.vue`)
    }

    routeObject.component = component

    // push new route entry to table
    routeTable.push(routeObject)
})

const router = new VueRouter({
    mode: 'history',
    routes: routeTable,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
})

router.beforeEach((to, from, next) => {
    if (to.path !== from.path) {
        store.dispatch('LOAD_AND_REPLACE_QUERYDATA', { path: to.path })
    }
    if (from.name !== null) {
        store.commit('UPDATE_REFERRAL_ROUTE', from)
    }
    next()
})

export default router
