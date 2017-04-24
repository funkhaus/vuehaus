import Vue from 'vue'
import VueRouter from 'vue-router'
import store from 'src/store'
import registry from './registry'

Vue.use( VueRouter )

const routes = [
    { path: '*', component: registry }
]

const router = new VueRouter( {
    mode: 'history',
    routes: routes
} )

router.beforeEach( ( to, from, next ) => {
    if( to.path !== from.path ){
        store.dispatch( 'LOAD_AND_REPLACE_QUERYDATA', to.path )
    }
    next()
} )

export default router
