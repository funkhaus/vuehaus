<template>

    <transition
        name="transition-page-fade"
        @before-enter="beforeEnter"
        @enter="enter"
        @leave="leave"
        mode="out-in"
        :css="false">
        <slot></slot>
    </transition>

</template>

<script>
    import Velocity from 'velocity-animate'
    import store from 'src/store'

    export default {
        props: {
            speed: {
                type: Number,
                default: 600
            },
            easing: {
                type: String,
                default: 'easeInOut'
            }
        },
        methods: {
            beforeEnter (el, done) {
                el.style.opacity = 0
            },
            enter (el, done) {
                store.commit('SET_TRANSITIONING_IN', true)
                Velocity(el, { opacity: 1 }, {
                    duration: this.speed,
                    easing: this.easing,
                    complete: () => {
                        store.commit('SET_TRANSITIONING_IN', false)
                        done()
                    }
                })
            },
            leave (el, done) {
                store.commit('SET_TRANSITIONING_OUT', true)
                Velocity(el, { opacity: 0 }, {
                    duration: this.speed,
                    easing: this.easing,
                    complete: () => {
                        store.commit('SET_TRANSITIONING_OUT', false)
                        document.body.scrollTop = 0
                        done()
                    }
                })
            }
        }
    }
</script>
