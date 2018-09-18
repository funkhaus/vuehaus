const { RichText } = wp.editor

export default {
    text: (props, index) => {
        const { attributes, className, setAttributes } = props
        const key = `child${index}`
        const content = attributes[key]

        function onChangeContent(newContent) {
            console.log('test', { [key]: newContent })
            setAttributes({ [key]: newContent })
        }

        return (
            <RichText
                tagName="p"
                className={className}
                onChange={onChangeContent}
                value={content}
            />
        )
    }
}
