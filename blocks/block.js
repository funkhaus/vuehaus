var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType

registerBlockType('gutenberg-boilerplate-es5/hello-world-step-01', {
    title: 'Explore Text',
    description: '"Explore Our Opportunities" type',
    icon: 'universal-access-alt',
    category: 'custom-fh',

    edit: function() {
        return el('p', {}, 'Hello editor.')
    },

    save: function() {
        return el('p', {}, 'Hello saved content.')
    }
})
