<template>

    <main class="content fallback">
        <wp-content :html="$store.getters.post.content"/>
    </main>

</template>

<script>
export default {}
</script>

<style lang="scss">
@import 'src/styles/vars';
</style>
