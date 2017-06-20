import morlock from 'morlock'

// init morlock controllers
export const scroller = new morlock.ScrollController({
    debounceMs: 10
})
export const sizer = new morlock.ResizeController({
    debounceMs: 30
})
