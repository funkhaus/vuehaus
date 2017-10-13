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

const baseUrl = config.baseUrl

console.log(`Testing '${ baseUrl }'...`)

// create output folder with current timestamp
const outputDir = 'tests/output-' + Date.now()
fs.mkdirSync(outputDir)

const puppeteer = require('puppeteer');

(async () => {

    const browser = await puppeteer.launch()
    const page = await browser.newPage()

    let index = 1

    await page.goto(baseUrl)
    await page.screenshot({ path: outputDir + '/01 - home.png' })
    console.log(`Step ${ index } - opening '${ baseUrl }'...`)

    let currentPath = '/'

    for( let step of config.itinerary ){
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
                path: outputDir + `/${ screenshotName }.png`
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

    await browser.close()

    console.log('Tests complete! Opening results...')

    open(`${ outputDir }/01 - home.png`)
    open(outputDir)

})()
