<template>
    <li :class="classes">

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

        <ul v-if="hasSubMenu">
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
            return this.$route.path == this.item.relativePath
        },
        isParent(){
            // remove last directory from current route
            const strippedSlash = this.$route.path.replace(/\/$/g, '')
            const parentRoute = strippedSlash.replace(/\/[^\/]*$/g, '')
            return parentRoute == this.item.relativePath
        },
        isAncestor(){
            return ( !this.isActive && this.$route.path.includes( this.item.relativePath ) ) || this.isParent || this.item.relativePath == '/'
        },
        hasSubMenu(){
            return Object.keys(this.item.children).length > 0
        },
        classes(){
            return [
                'menu-item',
                { 'menu-item-has-children': this.hasSubMenu },
                { 'current-menu-item': this.isActive },
                { 'current-menu-parent': this.isParent },
                { 'current-menu-ancestor': this.isAncestor }
            ]
        }
    }
}

</script>
