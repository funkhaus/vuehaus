const { RichText } = wp.editor

export default {
    text: (props, child) => {
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
    }
}
