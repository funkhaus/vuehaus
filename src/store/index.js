/* global jsonData */
import Vuex from 'vuex'
import Vue from 'vue'
import cache from 'src/services/cache'

// add vuex
Vue.use( Vuex )

export default new Vuex.Store( {
    state: {
        site: jsonData.site,
        meta: jsonData.meta,
        loop: jsonData.loop,
        transitioning_in: false,
        transitioning_out: false,
        loaded: false
    },
    mutations: {
        'REPLACE_QUERYDATA': ( state, data ) => {
            state.site = data.site
            state.meta = data.meta
            state.loop = data.loop
            return state
        },
        'SET_TRANSITIONING_IN': (state, transitioning) => {
            state.transitioning_in = transitioning
            return state
        },
        'SET_TRANSITIONING_OUT': (state, transitioning) => {
            state.transitioning_out = transitioning
            return state
        },
        'SET_LOADED': (state, loaded) => {
            state.loaded = loaded || false
        }
    },
    actions: {
        'LOAD_AND_REPLACE_QUERYDATA': async ( context, payload ) => {

            const path = payload.path || ''

            // no cache? set it
            if ( !cache[payload.path] ){
                context.commit('SET_LOADED', false)
                const headers = new Headers({ 'Authorization': `Basic ${ btoa('flywheel:funkhaus') }` })
                cache[path] = fetch(`${path}?contentType=json`, { headers }).then(r => r.json())
            }

            // wait for data, replace
            const data = await cache[path]
            context.commit( 'REPLACE_QUERYDATA', data )
            context.commit( 'SET_LOADED', true )
        }
    },
    getters: {
        loading: state => {
            return !state.loaded
        }
    }
})
