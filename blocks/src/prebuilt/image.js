const { MediaUpload } = wp.editor
const { Button } = wp.components

export default {
    // attributes
    attributes: child => {
        return {
            mediaID: {
                type: 'number'
            },
            mediaURL: {
                type: 'string',
                source: 'attribute',
                selector: 'img',
                attribute: 'src'
            },
            width: {
                type: 'number'
            },
            height: {
                type: 'number'
            }
        }
    },

    // editing
    edit: (props, child) => {
        const { setAttributes, attributes } = props

        // try to find correct attributes, default to empty object
        const atts = attributes[child.name] || {}
        const { mediaID, mediaURL } = atts

        // selecting the image
        function onSelectImage(newImage) {
            // console.log(newImage)
            setAttributes({
                [child.name]: {
                    mediaURL: newImage.url,
                    mediaID: newImage.id,
                    width: newImage.width,
                    height: newImage.height
                }
            })
        }

        // edit block
        return (
            <MediaUpload
                onSelect={onSelectImage}
                type="image"
                value={mediaID}
                render={({ open }) => (
                    <Button
                        className={
                            mediaID ? 'image-button' : 'button button-large'
                        }
                        onClick={open}
                    >
                        {!mediaID ? 'Upload Image' : <img src={mediaURL} />}
                    </Button>
                )}
            />
        )
    },
    save: (props, child) => {
        const { attributes } = props

        // ignore if we don't have the correct content
        if (!attributes || !attributes[child.name]) {
            return ''
        }

        const { mediaID, mediaURL, width, height } = attributes[child.name]

        if (mediaURL) {
            return (
                <img
                    data-replace-with="responsive-image"
                    src={mediaURL}
                    data-wp-id={mediaID}
                    width={width}
                    height={height}
                />
            )
        }

        return ''
    }
}
