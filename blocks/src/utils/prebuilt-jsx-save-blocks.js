const { RichText } = wp.editor

export default {
    text: (props, child) => {
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
