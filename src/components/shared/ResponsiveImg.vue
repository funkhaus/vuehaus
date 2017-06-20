<template>
    <div
        :class="['image-module', { loading }]"
        :style="outerStyles"
        @mouseenter="$emit('mouseenter', $event)"
        @mouseleave="$emit('mouseleave', $event)">
        <div class="image-sizer" :style="sizerStyles"></div>
    </div>
</template>

<script>
    import imagesLoaded from 'imagesLoaded'

    export default {
        name: 'responsive-img',
        props: {
            src: String,
            height: [String, Number],
            width: [String, Number],
            color: {
                type: String,
                default: 'transparent'
            }
        },
        data () {
            return {
                loading: true
            }
        },
        mounted () {
            const img = new Image()
            img.src = this.src
            imagesLoaded(img, () => {
                this.loading = false
            })
        },
        computed: {
            aspectPadding () { return (parseInt(this.height) / parseInt(this.width) * 100) || 56.25 },
            outerStyles () {
                return {
                    'background-color': this.color
                }
            },
            sizerStyles () {
                return {
                    paddingBottom: `${this.aspectPadding}%`,
                    backgroundImage: `url(${ this.src })`
                }
            }
        }
    }

</script>

<style scoped>
    .image-module {
        position: relative;
        width: 100%;
    }
    .image-sizer {
        transition: opacity 0.6s ease;

        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }

    .loading .image-sizer {
        opacity: 0;
    }
</style>
