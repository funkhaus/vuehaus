/* global queryData */
import Vuex from 'vuex'
import $ from 'jquery'
import Vue from 'vue'

// add vuex
Vue.use( Vuex )

export default new Vuex.Store( {
    state: {
        queryData: queryData,
        transitioning_in: false,
        transitioning_out: false
    },
    mutations: {
        'REPLACE_QUERYDATA': ( state, data ) => {
            state.queryData = data
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
        'LOAD_AND_REPLACE_QUERYDATA': ( context, path ) => {

            // TODO: Set loading state

            $.ajax( {
                url: path,
                contentType: 'application/json',
                dataType: 'json',
                success: ( data ) => {
                    context.commit( 'REPLACE_QUERYDATA', data )
                },

                // TODO: Add action on error
                error: err => console.log( 'error', err )
            } )
        }
    }
} )
