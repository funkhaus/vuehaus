<template>

    <section class="front-page">

        <router-link to="/about">About</router-link>

        <p v-html="content"></p>
    </section>

</template>

<script>

export default {
    computed: {
        content(){
            return this.$store.state.queryData.data[0].post_content
        }
    }
}

</script>

<style>

</style>
