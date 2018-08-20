const { registerBlockType } = wp.blocks
const { RichText } = wp.editor

registerBlockType('custom-fh/block-slug', {
    title: 'Worker',
    description: 'The current block',
    icon: 'universal-access-alt',
    category: 'custom-fh',

    // This defines the shape of the data in the functions below
    attributes: {
        content: {
            type: 'array',
            source: 'children',
            selector: 'p'
        }
    },

    // Editor
    edit({ attributes, className, setAttributes }) {
        const { content } = attributes

        function onChangeContent(newContent) {
            setAttributes({ content: newContent })
        }

        return (
            <RichText
                tagName="p"
                className={className}
                onChange={onChangeContent}
                value={content}
            />
        )
    },

    // On save
    save({ attributes, className }) {
        const { content } = attributes

        return (
            <RichText.Content
                tagName="p"
                className={className}
                value={content}
            />
        )
    }
})
