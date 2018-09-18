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

    // build attributes
    const attributes = {}
    settings.content.map(child => {
        attributes[child.name] = {
            type: 'array',
            source: 'children',
            selector: `.${child.name}`
        }
    })

    // register desired block
    registerBlockType(`custom-fh/${settings.slug}`, {
        title: settings.name,
        description: settings.description,
        icon: settings.icon,
        category: 'custom-fh',

        attributes,

        // Editor
        edit(props) {
            const { attributes, className, setAttributes } = props
            const classes = `${settings.class} fh-custom-block`

            const output = settings.content.map(child =>
                editJsx[child.type](props, child)
            )

            return <div className={classes}>{output}</div>
        },

        // On save
        save(props) {
            const output = settings.content.map(child =>
                saveJsx[child.type](props, child)
            )

            return <div className={settings.class}>{output}</div>
        }
    })
}
