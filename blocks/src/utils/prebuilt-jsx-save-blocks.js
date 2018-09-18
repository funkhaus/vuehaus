const { RichText } = wp.editor

export default {
    text: (props, child) => {
        const { attributes } = props
        const key = child.name
        const content = attributes[key]
        const classes = `${child.name}`

        return (
            <RichText.Content className={classes} tagName="p" value={content} />
        )
    }
}
