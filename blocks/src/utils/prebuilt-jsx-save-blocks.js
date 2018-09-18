const { RichText } = wp.editor

export default {
    text: (props, index) => {
        const { attributes, className } = props
        const key = `child${index}`
        const content = attributes[key]

        return (
            <RichText.Content
                tagName="p"
                className={className}
                value={content}
            />
        )
    }
}
