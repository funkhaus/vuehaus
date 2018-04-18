import transitionSwitch from './switch'

export default {
    template: `
        <transition-switch :from="transitionFrom" :to="transitionTo">
            <slot />
        </transition-switch>
    `,
    components: {
        'transition-switch': transitionSwitch
    },
    data() {
        return {
            transitionFrom: null,
            transitionTo: null
        }
    },
    created() {
        this.transitionTo = this.$route
    },
    watch: {
        $route(to, from) {
            this.transitionFrom = from
            this.transitionTo = to
        }
    }
}
