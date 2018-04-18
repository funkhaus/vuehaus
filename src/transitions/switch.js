import defaultTransition from './fallback'
import _kebabCase from 'lodash/kebabCase'
import Velocity from 'velocity-animate'
import store from 'src/utils/store'
import _pick from 'lodash/pick'

// define names of function hooks
const enterFuncs = ['enter', 'beforeEnter', 'afterEnter']
const leaveFuncs = ['leave', 'beforeLeave', 'afterLeave']
const propNames = ['mode', 'appear']

// build functional component
export default {
    functional: true,
    render(createElement, context) {
        const fromName = _kebabCase(_get(context, 'props.from.name', 'none'))
        const toName = _kebabCase(_get(context, 'props.to.name', 'none'))

        // assume defaults
        let enterTrans = _pick(defaultTransition, enterFuncs)
        let leaveTrans = _pick(defaultTransition, leaveFuncs)
        let props = _pick(defaultTransition, propNames)

        // if there is a from-to transition file, use it
        try {
            const key = `${fromName}-${toName}`
            const trans = require(`./pages/${key}`).default
            enterTrans = _pick(trans, enterFuncs)
            leaveTrans = _pick(trans, leaveFuncs)
            props = { ...props, ..._pick(trans, propNames) }
        } catch (err) {
            // no from-to file, get individually

            // get transition for from
            if (context.props.from) {
                try {
                    leaveTrans = _pick(
                        require(`./pages/${fromName}`).default,
                        leaveFuncs
                    )
                } catch (e) {}
            }

            // get transition for to
            if (context.props.to) {
                try {
                    const trans = require(`./pages/${toName}`).default
                    enterTrans = _pick(trans, enterFuncs)
                    props = { ...props, ..._pick(trans, propNames) }
                } catch (e) {}
            }
        }

        const events = {
            ...enterTrans,
            ...leaveTrans
        }

        // define transition
        const data = {
            props: {
                css: false,
                ...props
            },
            on: {
                ...events,
                beforeEnter() {
                    store.commit('START_TRANSITIONING_IN')
                    if (events['beforeEnter'])
                        events['beforeEnter'].call(this, ...arguments)
                },
                afterEnter() {
                    store.commit('END_TRANSITIONING_IN')
                    if (events['afterEnter'])
                        events['afterEnter'].call(this, ...arguments)
                },
                beforeLeave() {
                    store.commit('START_TRANSITIONING_OUT')
                    if (events['beforeLeave'])
                        events['beforeLeave'].call(this, ...arguments)
                },
                afterLeave() {
                    store.commit('END_TRANSITIONING_OUT')
                    if (events['afterLeave'])
                        events['afterLeave'].call(this, ...arguments)
                }
            }
        }

        return createElement('transition', data, context.children)
    }
}
