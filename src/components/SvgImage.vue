<template>
    <div class="svg-wrapper" v-html="svg"></div>
</template>

<script>
    export default {
        template: '',
        props: {
            src: String
        },
        computed: {
            svg(){
                if( this.src ) {
                    return require(`src/svgs/${ this.src }`)
                }

                return ''
            }
        }
    }
</script>
