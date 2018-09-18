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
    settings.content.map((child, i) => {
        attributes[`child${i}`] = {
            type: 'array',
            source: 'children',
            selector: 'p'
        }
    })

    console.log(attributes)

    // register desired block
    registerBlockType(`custom-fh/${settings.slug}`, {
        title: settings.name,
        description: settings.description,
        icon: settings.icon,
        category: 'custom-fh',

        // This defines the shape of the data in the functions below
        // attributes: {
        //     content: {
        //         type: 'array',
        //         source: 'children',
        //         selector: 'p'
        //     }
        // },

        attributes,

        // Editor
        edit(props) {
            const { attributes, className, setAttributes } = props
            const classes = `${settings.class} fh-custom-block`
            const output = settings.content.map((child, i) =>
                editJsx[child](props, i)
            )

            return <div className={classes}>{output}</div>
        },

        // On save
        save(props) {
            const output = settings.content.map((child, i) =>
                saveJsx[child](props, i)
            )

            return <div className={settings.class}>{output}</div>
        }
    })
}
