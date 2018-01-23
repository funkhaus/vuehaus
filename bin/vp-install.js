/* eslint-disable */
const fs = require('fs-extra')
const colors = require('colors')

console.log('Preparing Vuepress...'.yellow)

// Try to rename deploy example path
const deployExamplePath = './.deploy.config.example.js'
if( fs.existsSync(deployExamplePath) ){
    fs.renameSync(deployExamplePath, './.deploy.config.js')
}

// Other post-install scripts go here

console.log('Vuepress installation complete!'.green)
