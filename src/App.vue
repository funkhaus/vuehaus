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
import _throttle from 'lodash/throttle'
import router from 'src/utils/router'
import store from 'src/utils/store'
import _kebabCase from 'lodash/kebabCase'
import CacheCrawler from 'src/utils/cache-crawler'
import { autoBlur } from 'auto-blur'

export default {
    el: '#app',
    store,
    router,
    data() {
        return {
            winHeight: window.innerHeight,
            winWidth: window.innerWidth,
            sTop: 0
        }
    },
    watch: {
        // change page title when title changes
        '$store.state.meta.title'(title) {
            if (!document) return
            const el = document.createElement('div')
            el.innerHTML = title
            title = el.innerText || el.textContent
            document.title = title
        }
    },
    mounted() {
        window.addEventListener('resize', _throttle(this.onResize, 30))
        window.addEventListener('scroll', _throttle(this.onScroll, 10))

        // start cache crawler after page has fully loaded
        window.onload = function() {
            CacheCrawler.onNewPage()
        }

        // Close menu on esc key
        window.addEventListener('keydown', key => {
            if (key && key.keyCode == 27) {
                // Escape key
                this.$store.commit('CLOSE_MENU')
            }
        })

        // autoblur (see https://github.com/safrmo/auto-blur)
        autoBlur()
    },
    methods: {
        onResize() {
            this.winWidth = window.innerWidth
            this.winHeight = window.innerHeight

            this.$emit('throttled.resize')
        },
        onScroll() {
            this.sTop = window.pageYOffset || document.documentElement.scrollTop

            this.$emit('throttled.scroll')
        }
    },
    computed: {
        classes() {
            return [
                'container',
                `breakpoint-${this.breakpoint}`,
                `route-${_kebabCase(this.$route.name)}`,
                { loading: this.$store.getters.loading },
                { 'is-touch': this.$store.state.site.isMobile },
                { 'not-touch': !this.$store.state.site.isMobile }
            ]
        },
        breakpoint() {
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
