import buildBlock from './utils/builder'

buildBlock({
    name: 'Split Block',
    slug: 'split-block',
    description: 'Image next to text',
    class: 'split-block',
    content: [
        <h2>Title</h2>,
        { name: 'content', type: 'text' },
        <h2>Byline</h2>,
        { name: 'byline', type: 'text' }
    ]
})
