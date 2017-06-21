<template>
    <div class="main-menu">

        <div class="menu-items">
            <ul>
                <li v-for="(item, i) in menuItems" :class="{ active: $route.path == item.relativePath }">
                    <a
                        v-if="item.is_external"
                        :href="item.permalink"
                        target="_blank">
                        {{ item.title }}
                    </a>
                    <router-link
                        v-else
                        :to="item.relativePath">
                        {{ item.title }}
                    </router-link>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
    // TODO: make template work recursively with sub-menus
    // TODO: Better active class handling

    export default {
        name: 'main-menu',
        computed: {
            menuItems () {
                return _.get(this, '$store.state.queryData.shared.mainMenu.items') || []
            }
        }
    }
</script>

<style scoped>
    ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
</style>
