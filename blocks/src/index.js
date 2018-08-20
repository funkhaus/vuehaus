const { registerBlockType } = wp.blocks

registerBlockType('custom-fh/template-block', {
    title: 'Worker Block',
    description: 'The block template.',
    icon: 'universal-access-alt',
    category: 'custom-fh',

    edit() {
        return <p>Hello editor.</p>
    },

    save() {
        return <p>Hello saved content.</p>
    }
})
