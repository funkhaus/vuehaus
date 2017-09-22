/* global jsonData */
import Vuex from 'vuex'
import Vue from 'vue'

const cache = {
    '/': Promise.resolve(jsonData)
}

// add vuex
Vue.use( Vuex )

export default new Vuex.Store( {
    state: {
        site: jsonData.site,
        meta: jsonData.meta,
        page: jsonData.page,
        transitioning_in: false,
        transitioning_out: false
    },
    mutations: {
        'REPLACE_QUERYDATA': ( state, data ) => {
            state.site = data.site
            state.meta = data.meta
            state.page = data.page
            return state
        },
        'SET_TRANSITIONING_IN': (state, transitioning) => {
            state.transitioning_in = transitioning
            return state
        },
        'SET_TRANSITIONING_OUT': (state, transitioning) => {
            state.transitioning_out = transitioning
            return state
        }
    },
    actions: {
        'LOAD_AND_REPLACE_QUERYDATA': async ( context, path ) => {

            // no cache? set it
            if ( !cache[path] ){
                const headers = new Headers({ 'Authorization': `Basic ${ btoa('flywheel:funkhaus') }` })
                cache[path] = fetch(`${path}?contentType=json`, { headers }).then(r => r.json())
            }

            // wait for data, replace
            const data = await cache[path]
            context.commit( 'REPLACE_QUERYDATA', data )
        }
    }
})
