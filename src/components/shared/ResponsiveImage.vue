<template>
    <div
        :class="['image-module', { loading }]"
        :style="outerStyles"
        @mouseenter="$emit('mouseenter', $event)"
        @mouseleave="$emit('mouseleave', $event)">
        <div class="image-sizer" :style="sizerStyles" v-html="imageTag"></div>
    </div>
</template>

<script>
    import imagesLoaded from 'imagesLoaded'
    import _get from 'lodash/get'

    export default {
        props: {
            object: {
                type: Object,
                default(){
                    return {}
                }
            },
            src: String,
            height: [String, Number],
            width: [String, Number],
            aspect: [String, Number],
            size: {
                type: String,
                default: 'full'
            },
            color: {
                type: String,
                default: 'transparent'
            },
            'respect-max': {
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
                loading: true,
                loadedImage: false,
                imageWidth: 0,
                imageHeight: 0
            }
        },
        mounted () {
            const img = new Image()
            img.src = this.parsedSrc

            // set up height/width if we have an object
            if( this.object ){
                this.imageWidth = _get(this.object, `sizes.${ this.size }.width`)
                this.imageHeight = _get(this.object, `sizes.${ this.size }.height`)
            }

            imagesLoaded(img, () => {
                this.loading = false

                // save loaded image
                this.loadedImage = img

                // update stats
                this.imageWidth = img.width
                this.imageHeight = img.height
            })
        },
        computed: {
            parsedSrc(){
                if( this.src ) return this.src

                return _get(this.object, `sizes.${ this.size }.url`)
            },
            parsedHeight(){
                // default to defined height
                if( this.height ) return parseInt(this.height)

                return this.imageHeight
            },
            parsedWidth(){
                // default to defined width
                if( this.width ) return parseInt(this.width)

                return this.imageWidth
            },
            aspectPadding () {
                const calculatedAspect = this.parsedHeight / this.parsedWidth * 100
                return this.aspect || calculatedAspect || 56.25
            },
            outerStyles () {
                return {
                    'background-color': _get(this.object, 'primary_color', false) || this.color,
                    'max-width': this.respectMax ? `${ this.parsedWidth }px` : 'initial',
                    'max-height': this.respectMax ? `${ this.parsedHeight }px` : 'initial'
                }
            },
            sizerStyles () {
                return {
                    paddingBottom: `${this.aspectPadding}%`,
                    backgroundImage: `url(${ this.parsedSrc })`
                }
            },
            imageTag(){
                return _get(this.object, `sizes.${ this.size }.html`)
            }
        }
    }

</script>

<style>
    .image-module {
        position: relative;
        width: 100%;
    }
    .image-sizer {
        transition: opacity 0.6s ease;
        position: relative;

        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }
    .image-sizer img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
    }

    .loading .image-sizer {
        opacity: 0;
    }
</style>
