/* global queryData */
import Vue from 'vue'
import Vuex from 'vuex'
import $ from 'jquery'
// add vuex
Vue.use( Vuex )

export default new Vuex.Store( {
    state: {
        state: '',
        queryData: queryData
    },
    mutations: {
        'REPLACE_QUERYDATA': ( state, data ) => {
            state.queryData = data
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
