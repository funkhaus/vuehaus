<template>
    <div class="main" v-html="query.data[0].post_content">

        <span v-if="!query">Error loading!</span>

    </div>
</template>

<script>
    import replaceSVGs from 'replaceSVGs'

    export default {
        el: '#app',
        data () {
            return {
                query: queryData
            }
        },
        created () {
            console.log(queryData)
        }
    }
</script>
