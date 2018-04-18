export default {
    mode: 'out-in',
    async enter(el, done) {
        window.scrollTo(0, 0)
        return done()
    },
    async leave(el, done) {
        window.scrollTo(0, 0)
        return done()
    }
}
