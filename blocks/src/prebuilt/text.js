const { RichText } = wp.editor

export default {
    attributes: child => {
        return {
            type: 'array',
            source: 'children',
            selector: `.${child.name}`
        }
    },
    edit: (props, child) => {
        const { attributes, className, setAttributes } = props
        const content = attributes[child.name]

        const classes = `${child.name} ${className}`

        function onChangeContent(newContent) {
            setAttributes({ [child.name]: newContent })
        }

        return (
            <RichText
                tagName="p"
                className={classes}
                onChange={onChangeContent}
                value={content}
            />
        )
    },
    save: (props, child) => {
        // data attributes passed from editor
        const { attributes } = props

        // get content and classes relevant to this particular block
        const content = attributes[child.name]
        const classes = `${child.name}`

        return (
            <RichText.Content className={classes} tagName="p" value={content} />
        )
    }
}
