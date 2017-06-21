<template>
    <div :class="['main', `breakpoint-${ breakpoint }`]">

        <router-view></router-view>

    </div>
</template>

<script>
    import { sizer, scroller } from 'src/morlock'
    import router from 'src/router'
    import store from 'src/store'

    export default {
        el: '#app',
        data () {
            return {
                winHeight: window.innerHeight,
                winWidth: window.innerWidth,
                sTop: 0
            }
        },
        created () {
            sizer.on('resize', e => {
                this.winWidth = e[0]
                this.winHeight = e[1]
            })
            scroller.on('scroll', top => this.sTop = top)
        },
        computed: {
            breakpoint () {
                return this.winWidth >= 750 ? 'desktop' : 'mobile'
            }
        }
        store,
        router
    }
</script>
