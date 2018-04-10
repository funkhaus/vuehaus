<template>
    <div :class="['svg-wrapper', { loading }]" v-html="innerMarkup" />
</template>

<script>
export default {
    template: '',
    props: {
        src: String,
        default: ''
    },
    data() {
        return {
            loadedSVG: '',
            loading: false
        }
    },
    mounted() {
        if (this.src && this.isAbsolute) this.fetchSVG()
    },
    watch: {
        isAbsolute(absolute) {
            if (absolute) this.fetchSVG()
        }
    },
    computed: {
        isAbsolute() {
            return Boolean(this.src.match(/^https?:\/\//g))
        },
        innerMarkup() {
            // no src? Stay empty
            if (!this.src) return ''

            // local URL? Attempt to get from webpack
            if (!this.isAbsolute) {
                try {
                    return require(`src/svgs/${this.src}`)
                } catch (e) {}
            }

            // no SVG loaded yet, show IMG tag
            if (!this.loadedSVG) {
                return `<img class="svg" src="${this.src}" />`
            }

            // return loaded SVG code
            return this.loadedSVG
        }
    },
    methods: {
        async fetchSVG() {
            // set state
            this.loading = true

            // fetch SVG and validate
            try {
                const response = await fetch(this.src, {
                    credentials: 'same-origin'
                })
                const mimeType = response.headers.get('content-type')
                if (mimeType === 'image/svg+xml') {
                    this.loadedSVG = await response.text()
                }
            } catch (e) {}
            return (this.loading = false)
        }
    }
}
</script>
