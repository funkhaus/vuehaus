/* global jsonData */
import App from './App.vue'
import Vue from 'vue'
import cache from 'src/utils/cache'

// register global components
// Vue.component('a-div', require('fh-components/a-div').default)
// Vue.component('flex-text', require('fh-components/flex-text').default)
Vue.component('hamburger-button', require('fh-components/hamburger-button').default)
Vue.component('responsive-image', require('fh-components/responsive-image').default)
// Vue.component('video-player', require('fh-components/video-player').default)
// Vue.component('wp-content', require('fh-components/wp-content').default)
Vue.component('wp-menu', require('fh-components/wp-menu').default)
Vue.component('menu-item', require('fh-components/wp-menu-item').default)
Vue.component('svg-image', require('src/components/SvgImage.vue'))
// Vue.component('transition-fade', require('fh-components/transition-fade').default)

// Register directives
// import fullHeight from 'fh-components/v-full-height'
// Vue.directive('full-height', fullHeight)

// save initial page cache - dumped onto page as `jsonData` by Rest-Easy
cache[window.location.pathname] = Promise.resolve(jsonData)

// boot app
new Vue( App )
