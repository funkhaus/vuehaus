/* global jsonData */
import Vuex from 'vuex'
import Vue from 'vue'
import cache from 'src/utils/cache'
import _get from 'lodash/get'
import CacheCrawler from 'src/utils/cache-crawler'

// add vuex
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        site: jsonData.site,
        meta: jsonData.meta,
        loop: jsonData.loop,
        loaded: true,
        menuOpened: false,
        referral: null,
        currentlyFetching: null
    },
    mutations: {
        REPLACE_QUERYDATA: (state, data) => {
            state.site = data.site
            state.meta = data.meta
            state.loop = data.loop

            // reboot cache-crawler
            CacheCrawler.onNewPage()

            return state
        },
        SET_LOADED: (state, loaded) => {
            state.loaded = loaded || false
        },
        OPEN_MENU: state => {
            state.menuOpened = true
        },
        CLOSE_MENU: state => {
            state.menuOpened = false
        },
        UPDATE_REFERRAL_ROUTE: (state, referral) => {
            state.referral = referral
        },
        SET_CURRENTLY_FETCHING: (state, { path }) => {
            state.currentlyFetching = path
        }
    },
    actions: {
        LOAD_AND_REPLACE_QUERYDATA: async (context, payload) => {
            const path = payload.path || ''

            // flag that we're looking for the given path
            context.commit('SET_CURRENTLY_FETCHING', { path })

            // no cache? set it
            if (!cache[payload.path]) {
                context.commit('SET_LOADED', false)
                cache[path] = fetch(`${path}?contentType=json`, {
                    credentials: 'same-origin'
                }).then(r => r.json())
            }

            // wait for data
            const data = await cache[path]

            // if we're still waiting for the data from this path, replace it and finish loading
            if (context.state.currentlyFetching == path) {
                context.commit('REPLACE_QUERYDATA', data)
                context.commit('SET_LOADED', true)
            }
        }
    },
    getters: {
        loading: state => {
            return !state.loaded
        },
        post: state => {
            // Return an empty object if we're still loading
            if (!state.loaded) {
                return {}
            }

            // This is a "post" in the sense of a WordPress post - the first result of the Loop
            return _get(state.loop, '[0]', {})
        },
        referralPath: state => {
            return _get(state.referral, 'fullPath', '')
        }
    }
})
