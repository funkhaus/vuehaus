<template>
    <div class="main" v-html="query.post_content">

        <span v-if="!query">Error loading!</span>

    </div>
</template>

<script>
    import replaceSVGs from 'replaceSVGs'

    export default {
        el: '#app',
        data () {
            return {
                query: queryData[0]
            }
        },
        created () {

        }
    }
</script>
