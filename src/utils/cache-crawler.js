import cache from 'src/utils/cache'
import store from 'src/utils/store'

// flatten n-dimensional array
// https://stackoverflow.com/a/15030117/3856675
function flatten(arr) {
    return arr.reduce(function (flat, toFlatten) {
        return flat.concat(Array.isArray(toFlatten) ? flatten(toFlatten) : toFlatten)
    }, [])
}

// CacheCrawler preloads linked pages into the Vuepress cache.
// It speeds up page navigation dramatically, but results in extra network requests, so it's turned off by default.
// You can activate it manually in store.js
class CacheCrawler {
    constructor(){
        // which URLs we're going to try to preload
        this.candidates = []
        // are we currently waiting for a response?
        this.fetching = false
        // current candidate URL index
        this.index = 0
    }

    onNewPage(){
        // reset index
        this.index = 0

        // Which URLs should we try to load?
        // URLs in all site menus:
        const menuCandidates = store.state.site.menus.map(menu => menu.items.map(item => item.relativePath))
        // URLs in the Loop:
        const loopCandidates = store.state.loop.map( page => {
            const output = [ page.relativePath ]
            if( page.related.children && page.related.children.length ){
                output.push( page.related.children.map(child => child.relativePath) )
            }
            return output
        })

        // add your own URLs to crawl here

        // list of locations where we want to look for links
        const candidateQueue = [
            menuCandidates,
            loopCandidates
        ]
        this.candidates = flatten(candidateQueue)

        // start the fetch loop
        if( !this.fetching ){
            this.goFetch()
        }
    }

    async goFetch(){
        this.fetching = true

        if( this.index > this.candidates.length - 1 ){
            // we've cycled through all available candidates, so exit for now
            this.fetching = false
            return
        }

        // save the URL to fetch
        const path = this.candidates[this.index++]

        // conditions when we should ignore this path
        const ignorePath = !path || typeof path != 'string' || !path.length || path.startsWith('#')

        if ( !ignorePath && !cache[path] ){
            // fetch the JSON data from the URL
            // ?contentType=json is a Rest-Easy convention, ensuring that we only get a JSON response
            cache[path] = await fetch(`${path}?contentType=json`, { credentials: 'same-origin' }).then(r => r.json())
        }

        // continue the fetch loop
        this.goFetch()
    }
}

export default new CacheCrawler()
