<template>
    <div :class="classes">

        <router-view></router-view>

    </div>
</template>

<script>
    import throttle from 'lodash/throttle'
    import router from 'src/router'
    import store from 'src/store'
    import _kebabCase from 'lodash/kebabCase'

    export default {
        el: '#app',
        store,
        router,
        data () {
            return {
                winHeight: window.innerHeight,
                winWidth: window.innerWidth,
                sTop: 0
            }
        },
        mounted () {
            window.addEventListener('resize', throttle(this.setWinSize, 30))
            window.addEventListener('scroll', throttle(this.setWinScroll, 10))
        },
        methods: {
            setWinSize () {
                this.winWidth = window.innerWidth
                this.winHeight = window.innerHeight
            },
            setWinScroll () {
                this.sTop = window.pageYOffset || document.documentElement.scrollTop
            }
        },
        computed: {
            classes () {
                return [
                    'container',
                    `breakpoint-${ this.breakpoint }`,
                    { loading: this.$store.getters.loading },
                    `route-${ _kebabCase(this.$route.name) }`
                ]
            },
            breakpoint () {
                return this.winWidth >= 750 ? 'desktop' : 'mobile'
            }
        }
    }
</script>
