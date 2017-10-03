<template>
    <li :class="{ active: isActive, 'in-active-tree': isAncestor }">

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

        <ul v-if="item.children">
            <menu-item
                v-for="(child, index) in item.children"
                :key="index"
                :item="child"/>
        </ul>

    </li>

</template>

<script>

export default {
    props: {
        item: {
            type: Object,
            default: () => {
                return {}
            }
        }
    },
    computed: {
        isActive(){
            return this.$route.path.replace(/\/*$/, '') == this.item.relativePath
        },
        isAncestor(){
            return this.item.relativePath.length > 1
                && !this.isActive
                && this.$route.path.includes( this.item.relativePath )
        }
    }
}

</script>
