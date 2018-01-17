<template>
    <div :class="classes">
        <site-header/>

        <router-view/>

        <transition name="fade">
            <div
                v-if="$store.state.menuOpened"
                class="click-blocker"
                @click.native="$store.commit('CLOSE_MENU')">

                <wp-menu name="Main Menu" @click.native="$store.commit('CLOSE_MENU')"/>
            </div>
        </transition>

    </div>
</template>

<script>
    import throttle from 'lodash/throttle'
    import router from 'src/utils/router'
    import store from 'src/utils/store'
    import _kebabCase from 'lodash/kebabCase'
    import SiteHeader from 'src/components/SiteHeader.vue'
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
        components: {
            'site-header': SiteHeader
        },
        watch: {
            // change page title when title changes
            '$store.state.meta.title' (title) {
                if ( !document ) return
                const el = document.createElement('div')
                el.innerHTML = title
                title = el.innerText || el.textContent
                document.title = title
            }
        },
        mounted () {
            window.addEventListener('resize', throttle(this.onResize, 30))
            window.addEventListener('scroll', throttle(this.onScroll, 10))

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

<style lang="scss">

    // import base styles and transitions
    @import 'src/styles/base';
    @import 'src/styles/transitions';

</style>
