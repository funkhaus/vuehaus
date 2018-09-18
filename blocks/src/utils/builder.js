// prep prebuilt components
import prebuilt from '../prebuilt'

const { registerBlockType } = wp.blocks
const { RichText } = wp.editor

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

    // build attributes according to content children
    const attributes = {}
    settings.content.map(child => {
        if (child.type && prebuilt[child.type]) {
            attributes[child.name] = prebuilt[child.type].attributes(child)
        }
    })

    // register desired block
    registerBlockType(`custom-fh/${settings.slug}`, {
        title: settings.name,
        description: settings.description,
        icon: settings.icon,
        category: 'custom-fh',

        // use attributes defined above
        attributes,

        // Editor
        edit(props) {
            const { attributes, className, setAttributes } = props
            const classes = `${settings.class} fh-custom-block`

            const output = settings.content
                .map(child => {
                    if (child.type && prebuilt[child.type]) {
                        return prebuilt[child.type].edit(props, child)
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
                    if (child.type && prebuilt[child.type]) {
                        return prebuilt[child.type].save(props, child)
                    }

                    return null
                })
                .filter(x => x)

            return <div className={settings.class}>{output}</div>
        }
    })
}
