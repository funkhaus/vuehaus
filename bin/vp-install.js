/* eslint-disable */
const fs = require('fs-extra')
const colors = require('colors')

console.log('Preparing Vuepress...'.yellow)
fs.renameSync('./.deploy.config.example.js', './.deploy.config.js')

console.log('Vuepress installation complete!'.green)
