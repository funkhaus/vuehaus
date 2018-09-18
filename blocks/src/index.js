import buildBlock from './utils/builder'

buildBlock({
    name: 'Split Block',
    description: 'Image next to text',
    class: 'split-block',
    content: [
        <h2>Test</h2>,
        { name: 'content', type: 'text' },
        <h2>Byline</h2>,
        { name: 'byline', type: 'text' }
    ]
})
