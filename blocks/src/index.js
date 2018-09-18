import buildBlock from './utils/builder'

buildBlock({
    name: 'Split Block',
    description: 'Image next to text',
    class: 'split-block',
    content: [
        { name: 'content', type: 'text' },
        { name: 'byline', type: 'text' }
    ]
})
