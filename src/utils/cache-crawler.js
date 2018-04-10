import cache from 'src/utils/cache'
import store from 'src/utils/store'

// flatten n-dimensional array
// https://stackoverflow.com/a/15030117/3856675
function flatten(arr) {
    return arr.reduce(function(flat, toFlatten) {
        return flat.concat(
            Array.isArray(toFlatten) ? flatten(toFlatten) : toFlatten
        )
    }, [])
}

class CacheCrawler {
    constructor() {
        this.candidates = []
        this.fetching = false
        this.index = 0
    }

    onNewPage() {
        // reset index
        this.index = 0

        // links in all site menus
        const menuCandidates = store.state.site.menus.map(menu =>
            menu.items.map(item => item.relativePath)
        )
        // links in the loop
        const loopCandidates = store.state.loop.map(page => {
            const output = [page.relativePath]
            if (page.related.children && page.related.children.length) {
                output.push(
                    page.related.children.map(child => child.relativePath)
                )
            }
            return output
        })

        // list of locations where we want to look for links
        const candidateQueue = [menuCandidates, loopCandidates]
        this.candidates = flatten(candidateQueue)

        if (!this.fetching) {
            this.goFetch()
        }
    }

    async goFetch() {
        this.fetching = true

        if (this.index > this.candidates.length - 1) {
            // we've cycled through all available candidates, so exit for now
            this.fetching = false
            return
        }

        const path = this.candidates[this.index++]

        // conditions when we should ignore this path
        const ignorePath =
            !path ||
            typeof path != 'string' ||
            !path.length ||
            path.startsWith('#')

        if (!ignorePath && !cache[path]) {
            const response = await fetch(`${path}?contentType=json`, {
                credentials: 'same-origin'
            }).then(res => (res.ok ? res.json() : false))

            // avoid caching non-200 responses
            if (response) {
                cache[path] = response
            }
        }

        this.goFetch()
    }
}

export default new CacheCrawler()
