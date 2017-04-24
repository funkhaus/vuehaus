import Vue from 'vue'
import frontPage from 'src/components/templates/front-page.vue'
import about from 'src/components/templates/about.vue'

// Any state on the server gets its own template
Vue.component( 'front-page-template', frontPage )
Vue.component( 'about-template', about )

export default {
    template: `<component :is="cmpState"></component>`,
    computed: {
        cmpState(){
            // TODO: Switch to _.get ?
            return this.$store.state.queryData.state + '-template'
        }
    }
}
