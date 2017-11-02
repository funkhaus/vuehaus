/* eslint no-console: "off" */
const open = require('open')
const fs = require('fs')
const padStart = require('string.prototype.padstart')
const commander = require('commander')

commander
    .version('1.0.0')
    .option('-c, --config [file]', 'Path to config JSON (relative from root directory) [.testrc.json]', '.testrc.json')
    .parse(process.argv)

const config = require('../' + commander.config)
const itinerary = config.itinerary || []
const screenshots = config.screenshots || []
const dimensions = config.dimensions || [1500]
const baseUrl = config.baseUrl

console.log(`Testing '${ baseUrl }'...`)

// create output folder with current timestamp
const outputDir = 'tests/output-' + Date.now()
let dir = outputDir
fs.mkdirSync(outputDir)

const puppeteer = require('puppeteer');

(async () => {

    const browser = await puppeteer.launch()
    const page = await browser.newPage()

    for( let dimension of dimensions ){

        if( typeof dimension == 'number' ){
            dimension = [dimension, 0]
        }

        console.log(`Running tests for ${ dimension.join('x') }...`)

        dir = outputDir + '/' + dimension.join('x')
        fs.mkdirSync(dir)

        const width = dimension[0]
        const height = dimension[1] || 1080

        await page.setViewport({ width, height })

        let index = 1

        let currentPath = '/'

        // Run standard screenshots
        let i = 1
        for( let path of screenshots ){
            await page.goto(baseUrl + '/' + path)

            // Scroll down the page and take screenshots along the way
            let currentScroll = 0
            let maxScroll = await page.evaluate(() => document.body.scrollHeight)
            while( currentScroll < maxScroll ) {
                await page.screenshot({ path: dir + `/${ i++ } - ${ path.replace(/\//g, '-') }.png` })
                await page.evaluate(() => {
                    window.scrollBy(0, window.innerHeight)
                })
                currentScroll += height
            }

            console.log(`Took screenshot(s) of '/${ path }'...`)
        }

        // Run itinerary
        for( let step of itinerary ){
            index++

            let output = `Step ${ index } - `

            switch( step.action ){

            case 'click':
                output += `clicking on '${ step.selector || 'a' }'...`
                const target = await page.$eval(step.selector || 'a', el => {
                    return el.getAttribute('href')
                })
                currentPath = target
                await page.click(step.selector || 'a')
                break

            case 'evaluate':
                // inject as element
                output += `running function...result:\n`
                const result = await page.evaluate(func => {
                    return Promise.resolve( eval(func) )
                }, step.function)
                output += result

                if( step.expected ){
                    const matches = result == step.expected
                    output += `\nResult of ${ result } ${ matches ? 'matches' : 'does not match' } expected value of ${ step.expected }`
                    output += `${ step.label ? ' ' + step.label : '' }.`
                }
                break

            case 'goto':
                output += `navigating to ${ step.path }...`
                currentPath = step.path
                await page.goto(`${ baseUrl }/${ step.path }`)
                break

            case 'hover':
                output += `hovering over '${ step.selector }'...`
                await page.hover( step.selector )
                break

            case 'scroll':
                let num
                if( step.number ){
                    num = step.number
                } else {
                    await page.evaluate(selector => {
                        num = document.querySelector(selector).getBoundingClientRect().top
                    }, [step.selector])
                }

                output += `scrolling ${ step.number ? 'by ' + step.number + 'px' : 'to \'' + step.selector + '\'' }...`
                await page.evaluate(() => {
                    window.scrollBy(0, num)
                })

                break
            }

            if( step.screenshot !== false ){
                output += `screenshot of ${ currentPath }...`
                let screenshotName = `${ padStart(index, 2, '0') } - `
                screenshotName += step.label || currentPath.replace(/\//g, ' - ')
                await page.screenshot({
                    path: dir + `/${ screenshotName }.png`
                })
            }

            console.log(output)

            if( step.message ){
                console.log(step.message)
            }
            if( step.waitFor == 'load' ){
                await page.waitForNavigation()
            }
        }


    }


    await browser.close()

    console.log('Tests complete! Opening results...')

    open(`${ outputDir }/01 - home.png`)
    open(outputDir)

})()
