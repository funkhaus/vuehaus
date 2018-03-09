<template>
    <div :class="classes">

        <router-view/>

    </div>
</template>

<script>
    import router from 'src/utils/router'
    import store from 'src/utils/store'
    import _kebabCase from 'lodash/kebabCase'
    import CacheCrawler from 'src/utils/cache-crawler'

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
        watch: {
            // change page title when title changes
            '$store.state.meta.title' (title) {
                if ( !document ) return

                // sanitize html and set as title
                const el = document.createElement('div')
                el.innerHTML = title
                title = el.innerText || el.textContent
                document.title = title
            }
        },
        mounted () {
            window.addEventListener('resize', this.onResize)
            window.addEventListener('scroll', this.onScroll)

            // start cache crawler after page has fully loaded
            window.onload = function(){
                CacheCrawler.onNewPage()
            }
        },
        methods: {
            onResize () {
                this.winWidth = window.innerWidth
                this.winHeight = window.innerHeight

                this.$emit('throttled.resize')
            },
            onScroll () {
                this.sTop = window.pageYOffset || document.documentElement.scrollTop

                this.$emit('throttled.scroll')
            }
        },
        computed: {
            classes () {
                return [
                    'container',
                    `breakpoint-${ this.breakpoint }`,
                    { loading: this.$store.getters.loading },
                    `route-${ _kebabCase(this.$route.name) }`,
                    { 'is-touch': this.$store.state.site.isMobile },
                    { 'not-touch': !this.$store.state.site.isMobile }
                ]
            },
            breakpoint () {
                return this.winWidth >= 750 ? 'desktop' : 'mobile'
            }
        }
    }
</script>
