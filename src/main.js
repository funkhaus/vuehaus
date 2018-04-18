/* global jsonData */
import App from './App.vue'
import Vue from 'vue'
import cache from 'src/utils/cache'
import Case from 'case'

// Register components in src/
// ===============================
const components = require.context('src/components', true)
components.keys().map(component => {
    // turn './ComponentName.vue' into 'component-name'
    const componentName = Case.kebab(
        component.replace(/^\.\//, '').replace(/\.vue$/, '')
    )
    // register new component globally
    Vue.component(componentName, components(component))
})

// Transitions controller
Vue.component('page-transition', require('src/transitions').default)

// Register outside components
// ===============================

// Register fh-components
// Vue.component('a-div', require('fh-components/a-div').default)
// Vue.component('flex-text', require('fh-components/flex-text').default)
Vue.component(
    'hamburger-button',
    require('fh-components/hamburger-button').default
)
Vue.component(
    'responsive-image',
    require('fh-components/responsive-image').default
)
// Vue.component('video-stage', require('fh-components/video-stage').default)
Vue.component('wp-content', require('fh-components/wp-content').default)
Vue.component('wp-menu', require('fh-components/wp-menu').default)
Vue.component('menu-item', require('fh-components/wp-menu-item').default)
// Vue.component('split-text', require('fh-components/split-text').default)
Vue.component('svg-image', require('src/components/SvgImage.vue'))
// Vue.component('transition-fade', require('fh-components/transition-fade').default)

// Register directives
// import fullHeight from 'fh-components/v-full-height'
// Vue.directive('full-height', fullHeight)
// import reverseHover from 'fh-components/v-reverse-hover'
// Vue.directive('reverse-hover', reverseHover)
// import inView from 'fh-components/v-in-view'
// Vue.directive('in-view', inView)

// save initial page cache - dumped onto page as `jsonData` by Rest-Easy
cache[window.location.pathname] = Promise.resolve(jsonData)

// boot app
new Vue(App)
