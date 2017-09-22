<template>
    <div :class="['main', `breakpoint-${ breakpoint }`]">

        <router-view></router-view>

    </div>
</template>

<script>
    import throttle from 'lodash/throttle'
    import router from 'src/router'
    import store from 'src/store'

    export default {
        el: '#app',
        store,
        router,
        data () {
            return {
                winHeight: window.innerHeight,
                winWidth: window.innerWidth,
                sTop: 0
            }
        },
        mounted () {
            window.addEventListener('resize', throttle(this.setWinSize, 30))
            window.addEventListener('scroll', throttle(this.setWinScroll, 10))
        },
        methods: {
            setWinSize () {
                this.winWidth = window.innerWidth
                this.winHeight = window.innerHeight
            },
            setWinScroll () {
                this.sTop = document.body.scrollTop
            }
        },
        computed: {
            breakpoint () {
                return this.winWidth >= 750 ? 'desktop' : 'mobile'
            }
        }
    }
</script>
