/* global _get */

/*
 * This file is used to house small pure utilities that we use frequesntly.
 * You can import these from anywhere in the src folder like this:
 * import { utilOne, utilTwo } from 'src/utils/shared'
 */

// Description: Take array of items and sort equally across n new arrays
// -- Returns: sorted buckets (array of arrays)
export const sortBuckets = (items, count = 2) => {
    const buckets = Array(count).fill(0).map(v => [])
    let pointer = 0

    items.forEach(item => {
        buckets[pointer].push(item)
        pointer = (pointer + 1) % count
    })

    return buckets
}

// Description: Easily preload an image by URL or full attachment object
// -- Returns: Promise that resolves to image DOM element
export const preloadImg = (img, size = 'full') => {
    return new Promise((resolve, reject) => {

        // assume URL provided
        let src = img

        // if object provided, assume it's
        // a rest-easy serialized attachment
        if ( typeof img === 'object' ) {
            src = _get(img, `sizes[${ size }].url`, '')
        }

        // ensure we have a URL and an image constructor
        if ( !src || !Image) return reject()

        // make image object and set callbacks
        const imageEl = new Image()
        imageEl.onload = () => resolve(imageEl)
        imageEl.onerror = reject
        return imageEl.src = src
    })
}

// Description: Easily preload a video file by URL or full attachment object
// -- Returns: Promise that resolves to video DOM element
export const preloadVid = (vid) => {
    return new Promise((resolve, reject) => {

        // assume URL provided
        let src = vid

        // if object provided, assume it's
        // a rest-easy serialized attachment
        if ( typeof vid === 'object' ) {
            src = _get(vid, `meta.custom_video_url`) || ''
        }

        // ensure we have a URL and a document object
        if ( !src || !document) return reject()

        // make DOM element and set callbacks
        const vidEl = document.createElement('video')
        vidEl.addEventListener('canplay', () => resolve(vidEl))
        vidEl.addEventListener('error', reject)
        return vidEl.src = src
    })
}

// Description: Tiny helper function to promise-ify a timeout
// -- Returns: Promise
export const wait = (time = 1000) => {
    return new Promise(resolve => {
        return setTimeout(resolve, time)
    })
}
