/* global jsonData */
import App from './App.vue'
import Vue from 'vue'
import cache from 'src/utils/cache'
import _kebabCase from 'lodash/kebabCase'

// Register components in src/
// ===============================
const components = require.context('src/components', true)
components.keys().map(component => {
    // turn './ComponentName.vue' into 'component-name'
    const componentName = _kebabCase(
        component.replace(/^\.\//, '').replace(/\.vue$/, '')
    )
    // register new component globally
    Vue.component(componentName, components(component))
})

// Register outside components
// ===============================

// Register fh-components
// Vue.component('a-div', require('fh-components/a-div'))
// Vue.component('flex-text', require('fh-components/flex-text'))
Vue.component('hamburger-button', require('fh-components/hamburger-button'))
Vue.component('image-loader', require('fh-components/image-loader'))
// Vue.component('load-on-view', require('fh-components/load-on-view'))
// Vue.component('mailing-list', require('fh-components/mailing-list'))
// Vue.component('menu-item', require('fh-components/wp-menu-item'))
Vue.component('responsive-image', require('fh-components/responsive-image'))
// Vue.component('scroll-to', require('fh-components/scroll-to'))
// Vue.component('slide-show', require('fh-components/slide-show'))
// Vue.component('split-text', require('fh-components/split-text'))
Vue.component('svg-image', require('src/components/SvgImage.vue'))
// Vue.component('sticky-wrap', require('fh-components/sticky-wrap'))
// Vue.component('text-typer', require('fh-components/text-typer'))
// Vue.component('transition-fade', require('fh-components/transition-fade'))
// Vue.component('velocity-animate', require('fh-components/velocity-animate'))
// Vue.component('video-stage', require('fh-components/video-stage'))
Vue.component('wp-content', require('fh-components/wp-content'))
Vue.component('wp-menu', require('fh-components/wp-menu'))

// Register directives
// import animated from 'fh-components/v-animated'
// Vue.directive('animated', animated)
// import fullHeight from 'fh-components/v-full-height'
// Vue.directive('full-height', fullHeight)
// import inView from 'fh-components/v-in-view'
// Vue.directive('in-view', inView)
// import interact from 'fh-components/v-interact'
// Vue.directive('interact', interact)
// import keyDown from 'fh-components/v-keydown'
// Vue.directive('keydown', keyDown)
// import reverseHover from 'fh-components/v-reverse-hover'
// Vue.directive('reverse-hover', reverseHover)

// save initial page cache - dumped onto page as `jsonData` by Rest-Easy
cache[window.location.pathname] = Promise.resolve(jsonData)

// boot app
new Vue(App)
