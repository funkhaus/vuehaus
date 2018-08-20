var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    RichText = wp.editor.RichText

// Register your own blocks here.
/*
registerBlockType('theme-slug/block-name', {
    title: 'Block Name',
    description: 'Block description.',
    icon: 'universal-access-alt',
    category: 'custom-fh',

    edit: function() {
        return el('p', {}, 'Hello editor.')
    },

    save: function() {
        return el('p', {}, 'Hello saved content.')
    }
})
*/

registerBlockType('theme-slug/block-name', {
    title: 'Worker',
    description: 'Block description.',
    icon: 'universal-access-alt',
    category: 'custom-fh',

    // this controls the shape of `props` in the functions below
    attributes: {
        content: {
            type: 'array',
            source: 'children',
            selector: 'p'
        }
    },

    edit: function(props) {
        var content = props.attributes.content

        function onChangeContent(newContent) {
            props.setAttributes({ content: newContent })
        }

        return el(RichText, {
            tagName: 'p',
            className: props.className,
            onChange: onChangeContent,
            value: content
        })
    },

    save: function(props) {
        var content = props.attributes.content

        return el(RichText.Content, {
            tagName: 'p',
            className: props.className,
            value: content
        })
    }
})
