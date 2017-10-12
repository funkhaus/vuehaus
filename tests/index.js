/* eslint no-console: "off" */
const open = require('open')
const fs = require('fs')
const padStart = require('string.prototype.padstart')

// YOUR TEST URL HERE
const baseUrl = 'http://vuepress.dev'

// YOUR TEST PAGES HERE
const screenshotPaths = [
    'example-page',
    'example-page/detail'
]

console.log(`Testing '${ baseUrl }'...`)

// create output folder with current timestamp
const outputDir = 'tests/output-' + Date.now()
fs.mkdirSync(outputDir)

const puppeteer = require('puppeteer');

(async () => {

    const browser = await puppeteer.launch()
    const page = await browser.newPage()

    await page.goto(baseUrl)
    await page.screenshot({ path: outputDir + '/01 - home.png' })

    let index = 2

    for( let path of screenshotPaths ){
        await page.goto(`${ baseUrl }/${ path }`)
        await page.screenshot({ path: outputDir + `/${ padStart(index++, 2, '0') } - ${ path.replace('/', ' - ') }.png` })
    }

    await browser.close()

    console.log('Tests complete! Opening results...')

    open(`${ outputDir }/01 - home.png`)
    open(outputDir)

})()
