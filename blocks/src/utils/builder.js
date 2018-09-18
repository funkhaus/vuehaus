const { registerBlockType } = wp.blocks
const { RichText } = wp.editor

export default (opts = {}) => {
    const settings = {
        // defaults
        slug: 'block-slug',
        name: 'Example',
        description: '',
        icon: 'format-gallery',

        // passed values
        ...opts
    }

    // register desired block
    registerBlockType(`custom-fh/${settings.slug}`, {
        title: settings.name,
        description: settings.description,
        icon: settings.icon,
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
                <div className="fh-custom-block">
                    <RichText
                        tagName="p"
                        className={className}
                        onChange={onChangeContent}
                        value={content}
                    />
                </div>
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
}
