import buildBlock from './utils/builder'

// Define your blocks here!

// Example block
buildBlock({
    name: 'Title and Byline',
    slug: 'title-and-byline',
    description: 'A title and a byline.',
    class: 'split-block',
    content: [
        <h2>Title</h2>,
        { name: 'content', type: 'text' },
        <h2>Byline</h2>,
        { name: 'byline', type: 'text' }
    ]
})

buildBlock({
    name: 'lines and image',
    slug: 'lines-and-image',
    class: 'lines-and-image',
    content: [
        <h2>Line 1</h2>,
        { name: 'content', type: 'text' },
        <h2>Line 2</h2>,
        { name: 'byline', type: 'text' },
        <h2>Image</h2>,
        { name: 'image', type: 'image' }
    ]
})
