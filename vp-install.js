/* eslint-disable */
const fs = require('fs-extra')
const colors = require('colors')

console.log('Preparing Vuepress...'.yellow)
fs.renameSync('./.deployrc-example', './.deployrc')

console.log('Vuepress installation complete!'.green)
