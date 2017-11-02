/* global jsonData */
import './main.css'
import transitionPageFadeCMP from 'src/components/transitions/PageFade.vue'
import RspImageCMP from 'src/components/shared/ResponsiveImage.vue'
import inViewCMP from 'src/components/shared/InView.vue'
import App from './components/App.vue'
import Vue from 'vue'
import cache from 'src/services/cache'
import wpMenuCMP from 'src/components/shared/WpMenu.vue'
import menuItemCMP from 'src/components/shared/MenuItem.vue'

// register global components
Vue.component('transition-page-fade', transitionPageFadeCMP)
Vue.component('responsive-image', RspImageCMP)
Vue.component('in-view', inViewCMP)
Vue.component('wp-menu', wpMenuCMP)
Vue.component('menu-item', menuItemCMP)

// save initial page cache
cache[window.location.pathname] = Promise.resolve(jsonData)

// boot app
new Vue( App )
