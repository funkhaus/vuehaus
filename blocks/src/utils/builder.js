const { registerBlockType } = wp.blocks
const { RichText } = wp.editor
import editJsx from './prebuilt-jsx-edit-blocks'
import saveJsx from './prebuilt-jsx-save-blocks'

export default (opts = {}) => {
    const settings = {
        // defaults
        slug: 'block-slug',
        name: 'Example',
        description: '',
        icon: 'format-gallery',
        content: [],
        class: 'block-class',

        // passed values
        ...opts
    }

    // build attributes according to content child names
    const attributes = {}
    settings.content.map(child => {
        console.log(child)

        if (child.name) {
            // TODO: different values for different types
            attributes[child.name] = {
                type: 'array',
                source: 'children',
                selector: `.${child.name}`
            }
        }
    })

    // register desired block
    registerBlockType(`custom-fh/${settings.slug}`, {
        title: settings.name,
        description: settings.description,
        icon: settings.icon,
        category: 'custom-fh',

        // use attributes from earlier
        attributes,

        // Editor
        edit(props) {
            const { attributes, className, setAttributes } = props
            const classes = `${settings.class} fh-custom-block`

            const output = settings.content
                .map(child => {
                    if (child.name) {
                        return editJsx[child.type](props, child)
                    }

                    return child
                })
                .filter(x => x)

            return <div className={classes}>{output}</div>
        },

        // On save
        save(props) {
            const output = settings.content
                .map(child => {
                    if (child.name) {
                        return saveJsx[child.type](props, child)
                    }

                    return null
                })
                .filter(x => x)

            return <div className={settings.class}>{output}</div>
        }
    })
}
