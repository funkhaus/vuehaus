<template>
    <div :class="['menu', slug]">
        <ul class="menu-items">
            <menu-item
                v-for="(item, i) in menuItems"
                :key="i"
                :item="item"/>
        </ul>
    </div>
</template>

<script>

    export default {
        props: {
            slug: {
                type: String,
                default: 'main-menu'
            }
        },
        computed: {
            menuItems () {
                // find first menu that matches the given slug
                let menu = this.$store.state.site.menus.find( singleMenu => {
                    return singleMenu.slug == this.slug
                } )

                // fall back to first menu
                if( menu === undefined ){
                    menu = this.$store.state.site.menus[0]
                }

                return menu ? menu.items : false
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
