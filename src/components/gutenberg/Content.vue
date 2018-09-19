<template>

    <wp-content 
        class="gutenberg-content" 
        :html="html"
        :unwrap="unwrap"
        :replace="cmpReplace"
        :fitvids="fitvids"/>

</template>

<script>
export default {
    props: {
        html: {
            type: String,
            default: ``
        },
        unwrap: {
            type: [String, Array, Boolean],
            default: () => ['p > img', 'p > iframe']
        },
        replace: {
            type: Array,
            default: () => []
        },
        fitvids: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        cmpReplace() {
            const output = this.replace

            // replace images with responsive-images
            output.push({
                selector: '[data-replace-with]',
                callback: el => {
                    const src = el.getAttribute('src')
                    const height = el.getAttribute('height')
                    const width = el.getAttribute('width')

                    return `<responsive-image
                            src="${src}"
                            height="${height}"
                            width="${width}"/>`
                }
            })

            return output
        }
    }
}
</script>

<style lang="scss">
@import 'src/styles/vars';

.gutenberg-content {
}
</style>
