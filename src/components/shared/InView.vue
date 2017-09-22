<template>
    <div
        :class="['wiv-module', viewClass, { hovering }]"
        @mouseenter="$emit('mouseenter', $event)"
        @mouseleave="$emit('mouseleave', $event)">
        <slot></slot>
        <span v-html="arrowRight"></span>
    </div>
</template>

<script>
export default {
    name: 'in-view',
    data () {
        return {
            height: 0,
            top: 0
        }
    },
    mounted () {
        this.setRect()
        this.setHeight()
        window.addEventListener('resize', this.setRect)
        window.addEventListener('scroll', this.setHeight)
    },
    watch: {
        viewClass (newVal) {
            this.$emit('change', newVal)
        }
    },
    computed: {
        viewClass () {
            if ( this.top + this.height <= 0 && this.height ) return 'above-view'
            if ( this.top >= this.$root.winHeight ) return 'below-view'
            return 'in-view'
        },
        arrowRight () { return arrowRightSVG }
    },
    methods: {
        setRect () {
            const rect = this.$el.getBoundingClientRect()
            this.top = rect.top
        },
        setHeight () {
            this.height = this.$el.clientHeight
        }
    }
}

</script>
