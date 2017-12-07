<template>
    <div tabindex="1" class="fh-slideshow" @keydown.right="triggerNext" @keydown.left="triggerPrev">

        <transition :name="`fh-slide-${ direction }`">
            <div class="slide"
                v-if="activeSlide == i"
                v-for="(slide, i) in slides"
                :key="i"
            >
                <responsive-image color="#f1f1f1" :fill-space="true" :object="slide" />
            </div>
        </transition>

        <svg-image class="nav next" src="arrow-right.svg" @click.native="triggerNext" />
        <svg-image class="nav prev" src="arrow-left.svg" @click.native="triggerPrev" />

        <div class="pagination">
            <div v-for="(s, i) in slides" :class="['pagination-item', { active: activeSlide == i }]"></div>
        </div>

    </div>
</template>

<script>
    import _get from 'lodash/get'

    export default {
        props: {
            loop: {
                type: Boolean,
                default: true
            },
            interval: {
                type: Number,
                default: 3500
            },
            running: {
                type: Boolean,
                default: true
            }
        },
        data () {
            return {
                activeSlide: 0,
                direction: 'left',
                timer: null
            }
        },
        mounted () {
            this.startTimer()
        },
        destroyed () {
            this.stopTimer()
        },
        watch: {
            activeSlide (to, from) {
                if ( to > from ) this.direction = 'left'
                else this.direction = 'right'
            }
        },
        methods: {
            triggerNext () {

                // restart timer
                this.stopTimer()
                this.startTimer()

                // set next or first
                this.activeSlide++
                if ( this.loop ) this.activeSlide = (this.activeSlide + this.slides.length) % this.slides.length
                else this.activeSlide = Math.min(this.activeSlide, this.slides.length - 1)
            },
            triggerPrev () {

                // restart timer
                this.stopTimer()
                this.startTimer()

                // set prev or last
                this.activeSlide--
                if ( this.loop ) this.activeSlide = (this.activeSlide + this.slides.length) % this.slides.length
                else this.activeSlide = Math.max(this.activeSlide, 0)
            },
            startTimer () {
                // start auto timer
                return this.timer = setInterval(() => {
                    if ( this.running ) this.triggerNext()
                }, this.interval)
            },
            stopTimer () {
                // kill timer
                return clearInterval(this.timer)
            }
        },
        computed: {
            slides () {
                const slides = _get(this.$store.getters, 'post.attachedMedia', [])
                return [...slides, ...slides]
            }
        }
    }
</script>

<style lang="scss">

    .fh-slideshow {
        position: relative;
        overflow: hidden;
        height: 500px;

        &:focus {
            outline: none;
        }
        .slide {
            height: 100%;
            width: 100%;
        }

        // next/prev
        .nav {
            position: absolute;
            margin-top: 10px;
            cursor: pointer;
            top: 50%;
        }
        .nav.next {
            right: 10px;
        }
        .nav.prev {
            left: 10px;
        }

        //pagination
        .pagination {
            text-align: center;
            position: absolute;
            padding: 20px;
            bottom: 0;
            right: 0;
            left: 0;
        }
        .pagination-item {
            background-color: rgba(255, 255, 255, 0.5);
            display: inline-block;
            margin: 0 10px;
            height: 10px;
            width: 10px;
        }
        .pagination-item.active {
            background-color: rgba(255, 255, 255, 1);
        }
    }

    // transition
    .fh-slide-left-enter-active,
    .fh-slide-left-leave-active,
    .fh-slide-right-enter-active,
    .fh-slide-right-leave-active {
        transition: transform 0.5s ease;
        position: absolute;
        left: 0;
        top: 0;
    }
    .fh-slide-left-enter,
    .fh-slide-right-leave-to {
        transform: translateX(100%);
    }
    .fh-slide-left-leave-to,
    .fh-slide-right-enter {
        transform: translateX(-100%);
    }


</style>
