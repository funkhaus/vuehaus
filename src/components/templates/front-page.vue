<template>

    <section class="front-page">

        <router-link to="/about">About</router-link>

        <p v-html="content"></p>
    </section>

</template>

<script>
import _ from 'lodash'

export default {
    computed: {
        content(){
            const pageData = _.get(this.$store, 'state.queryData.data[0].content')
            return pageData
        }
    }
}

</script>

<style>

</style>
