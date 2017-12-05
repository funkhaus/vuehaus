/* global jsonData */
import App from './App.vue'
import Vue from 'vue'
import cache from 'src/utils/cache'

// import transitionPageFadeCMP from 'src/components/transitions/PageFade.vue'
// import RspImageCMP from 'src/components/shared/ResponsiveImage.vue'
// import inViewCMP from 'src/components/shared/InView.vue'
// import wpMenuCMP from 'src/components/shared/WpMenu.vue'
// import menuItemCMP from 'src/components/shared/MenuItem.vue'
// import SvgImage from 'src/components/shared/SvgImage.vue'

// register global components
// Vue.component('transition-page-fade', transitionPageFadeCMP)
// Vue.component('responsive-image', RspImageCMP)
// Vue.component('in-view', inViewCMP)
Vue.component('wp-menu', require('fh-components/wp-menu').default)
Vue.component('wp-menu-item', require('fh-components/wp-menu-item').default)
Vue.component('svg-image', require('src/components/SvgImage.vue').default)

// save initial page cache - dumped onto page as `jsonData` by Rest-Easy
cache[window.location.pathname] = Promise.resolve(jsonData)

// boot app
new Vue( App )
