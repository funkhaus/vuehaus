/* global jsonData */
import App from './App.vue'
import Vue from 'vue'
import cache from 'src/utils/cache'

// register global components
Vue.component('responsive-image', require('fh-components/responsive-image').default)
Vue.component('wp-menu', require('fh-components/wp-menu').default)
Vue.component('menu-item', require('fh-components/wp-menu-item').default)
Vue.component('svg-image', require('src/components/SvgImage.vue'))

// save initial page cache - dumped onto page as `jsonData` by Rest-Easy
cache[window.location.pathname] = Promise.resolve(jsonData)

// boot app
new Vue( App )
