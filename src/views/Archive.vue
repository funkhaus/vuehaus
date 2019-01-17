<template>

    <main class="content archive">

        <!-- infinite scroll (assumes news-block component):

        <news-block
            v-for="(block, i) in gridPosts"
            :key="i"
            :newsData="block"
        />

        <load-on-view
            :url="nextPageLink"
            @on-json="pipePosts"
        />

        -->

    </main>

</template>

<script>
export default {
    /* infinite scroll:
    data() {
        return {
            nextPageLink: '',
            lazyLoaded: []
        }
    },
    mounted() {
        this.nextPageLink =
            _get(this.$store, 'state.site.meta.next_post_link', {}) +
            '?contentType=json'
    },
    computed: {
        gridPosts() {
            let output = _get(this.$store, 'state.loop', [])
            output = output.concat(this.lazyLoaded)
            return output
        }
    },
    methods: {
        pipePosts(res) {
            this.lazyLoaded = this.lazyLoaded.concat(res.loop)
            this.nextPageLink = res.meta.nextPage
        }
    }
    */
}
</script>

<style>
</style>
