/* global jsonData */
import App from './App.vue'
import Vue from 'vue'
import cache from 'src/utils/cache'

// save initial page cache - dumped onto page as `jsonData` by Rest-Easy
cache[window.location.pathname] = Promise.resolve(jsonData)

// boot app
new Vue( App )
