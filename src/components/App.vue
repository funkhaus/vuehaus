<template>
    <div class="main">

        <router-view></router-view>

    </div>
</template>

<script>
    import store from 'src/store'
    import router from 'src/router'

    export default {
        el: '#app',
        store,
        router
    }
</script>
