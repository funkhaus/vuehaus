import Vuex from 'vuex'

export default {
    state: {
        transitioningIn: false,
        transitioningOut: false
    },
    mutations: {
        START_TRANSITIONING_IN: state => {
            state.transitioningIn = true
        },
        END_TRANSITIONING_IN: state => {
            state.transitioningIn = false
        },
        START_TRANSITIONING_OUT: state => {
            state.transitioningOut = true
        },
        END_TRANSITIONING_OUT: state => {
            state.transitioningOut = false
        }
    },
    getters: {
        transitioning(state) {
            return state.transitioningIn || state.transitioningOut
        }
    }
}
