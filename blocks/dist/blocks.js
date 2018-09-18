/******/ ;(function(modules) {
    // webpackBootstrap
    /******/ // The module cache
    /******/ var installedModules = {} // The require function
    /******/
    /******/ /******/ function __webpack_require__(moduleId) {
        /******/
        /******/ // Check if module is in cache
        /******/ if (installedModules[moduleId]) {
            /******/ return installedModules[moduleId].exports
            /******/
        } // Create a new module (and put it into the cache)
        /******/ /******/ var module = (installedModules[moduleId] = {
            /******/ i: moduleId,
            /******/ l: false,
            /******/ exports: {}
            /******/
        }) // Execute the module function
        /******/
        /******/ /******/ modules[moduleId].call(
            module.exports,
            module,
            module.exports,
            __webpack_require__
        ) // Flag the module as loaded
        /******/
        /******/ /******/ module.l = true // Return the exports of the module
        /******/
        /******/ /******/ return module.exports
        /******/
    } // expose the modules object (__webpack_modules__)
    /******/
    /******/
    /******/ /******/ __webpack_require__.m = modules // expose the module cache
    /******/
    /******/ /******/ __webpack_require__.c = installedModules // define getter function for harmony exports
    /******/
    /******/ /******/ __webpack_require__.d = function(exports, name, getter) {
        /******/ if (!__webpack_require__.o(exports, name)) {
            /******/ Object.defineProperty(exports, name, {
                /******/ configurable: false,
                /******/ enumerable: true,
                /******/ get: getter
                /******/
            })
            /******/
        }
        /******/
    } // getDefaultExport function for compatibility with non-harmony modules
    /******/
    /******/ /******/ __webpack_require__.n = function(module) {
        /******/ var getter =
            module && module.__esModule
                ? /******/ function getDefault() {
                      return module['default']
                  }
                : /******/ function getModuleExports() {
                      return module
                  }
        /******/ __webpack_require__.d(getter, 'a', getter)
        /******/ return getter
        /******/
    } // Object.prototype.hasOwnProperty.call
    /******/
    /******/ /******/ __webpack_require__.o = function(object, property) {
        return Object.prototype.hasOwnProperty.call(object, property)
    } // __webpack_public_path__
    /******/
    /******/ /******/ __webpack_require__.p = '' // Load entry module and return exports
    /******/
    /******/ /******/ return __webpack_require__((__webpack_require__.s = 0))
    /******/
})(
    /************************************************************************/
    /******/ [
        /* 0 */
        /***/ function(module, exports, __webpack_require__) {
            __webpack_require__(1)
            module.exports = __webpack_require__(3)

            /***/
        },
        /* 1 */
        /***/ function(module, __webpack_exports__, __webpack_require__) {
            'use strict'
            Object.defineProperty(__webpack_exports__, '__esModule', {
                value: true
            })
            /* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_builder__ = __webpack_require__(
                2
            )

            Object(
                __WEBPACK_IMPORTED_MODULE_0__utils_builder__['a' /* default */]
            )({
                name: 'Split Block',
                description: 'Image next to text',
                class: 'split-block',
                content: [
                    wp.element.createElement('h2', null, 'Test'),
                    { name: 'content', type: 'text' },
                    wp.element.createElement('h2', null, 'Byline'),
                    { name: 'byline', type: 'text' }
                ]
            })

            /***/
        },
        /* 2 */
        /***/ function(module, __webpack_exports__, __webpack_require__) {
            'use strict'
            /* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__prebuilt_jsx_edit_blocks__ = __webpack_require__(
                8
            )
            /* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__prebuilt_jsx_save_blocks__ = __webpack_require__(
                9
            )
            var _extends =
                Object.assign ||
                function(target) {
                    for (var i = 1; i < arguments.length; i++) {
                        var source = arguments[i]
                        for (var key in source) {
                            if (
                                Object.prototype.hasOwnProperty.call(
                                    source,
                                    key
                                )
                            ) {
                                target[key] = source[key]
                            }
                        }
                    }
                    return target
                }

            var registerBlockType = wp.blocks.registerBlockType
            var RichText = wp.editor.RichText

            /* harmony default export */ __webpack_exports__['a'] = function() {
                var opts =
                    arguments.length > 0 && arguments[0] !== undefined
                        ? arguments[0]
                        : {}

                var settings = _extends(
                    {
                        // defaults
                        slug: 'block-slug',
                        name: 'Example',
                        description: '',
                        icon: 'format-gallery',
                        content: [],
                        class: 'block-class'
                    },
                    opts
                )

                // build attributes according to content child names
                var attributes = {}
                settings.content.map(function(child) {
                    console.log(child)

                    if (child.name) {
                        // TODO: different values for different types
                        attributes[child.name] = {
                            type: 'array',
                            source: 'children',
                            selector: '.' + child.name
                        }
                    }
                })

                // register desired block
                registerBlockType('custom-fh/' + settings.slug, {
                    title: settings.name,
                    description: settings.description,
                    icon: settings.icon,
                    category: 'custom-fh',

                    // use attributes from earlier
                    attributes: attributes,

                    // Editor
                    edit: function edit(props) {
                        var attributes = props.attributes,
                            className = props.className,
                            setAttributes = props.setAttributes

                        var classes = settings.class + ' fh-custom-block'

                        var output = settings.content
                            .map(function(child) {
                                if (child.name) {
                                    return __WEBPACK_IMPORTED_MODULE_0__prebuilt_jsx_edit_blocks__[
                                        'a' /* default */
                                    ][child.type](props, child)
                                }

                                return child
                            })
                            .filter(function(x) {
                                return x
                            })

                        return wp.element.createElement(
                            'div',
                            { className: classes },
                            output
                        )
                    },

                    // On save
                    save: function save(props) {
                        var output = settings.content
                            .map(function(child) {
                                if (child.name) {
                                    return __WEBPACK_IMPORTED_MODULE_1__prebuilt_jsx_save_blocks__[
                                        'a' /* default */
                                    ][child.type](props, child)
                                }

                                return null
                            })
                            .filter(function(x) {
                                return x
                            })

                        return wp.element.createElement(
                            'div',
                            { className: settings.class },
                            output
                        )
                    }
                })
            }

            /***/
        },
        /* 3 */
        /***/ function(module, exports) {
            // removed by extract-text-webpack-plugin
            /***/
        },
        ,
        ,
        ,
        ,
        /* 4 */ /* 5 */ /* 6 */ /* 7 */ /* 8 */
        /***/ function(module, __webpack_exports__, __webpack_require__) {
            'use strict'
            function _defineProperty(obj, key, value) {
                if (key in obj) {
                    Object.defineProperty(obj, key, {
                        value: value,
                        enumerable: true,
                        configurable: true,
                        writable: true
                    })
                } else {
                    obj[key] = value
                }
                return obj
            }

            var RichText = wp.editor.RichText

            /* harmony default export */ __webpack_exports__['a'] = {
                text: function text(props, child) {
                    var attributes = props.attributes,
                        className = props.className,
                        setAttributes = props.setAttributes

                    var content = attributes[child.name]

                    var classes = child.name + ' ' + className

                    function onChangeContent(newContent) {
                        setAttributes(
                            _defineProperty({}, child.name, newContent)
                        )
                    }

                    return wp.element.createElement(RichText, {
                        tagName: 'p',
                        className: classes,
                        onChange: onChangeContent,
                        value: content
                    })
                }
            }

            /***/
        },
        /* 9 */
        /***/ function(module, __webpack_exports__, __webpack_require__) {
            'use strict'
            var RichText = wp.editor.RichText

            /* harmony default export */ __webpack_exports__['a'] = {
                text: function text(props, child) {
                    // data attributes passed from editor
                    var attributes = props.attributes

                    // get content and classes relevant to this particular block

                    var content = attributes[child.name]
                    var classes = '' + child.name

                    return wp.element.createElement(RichText.Content, {
                        className: classes,
                        tagName: 'p',
                        value: content
                    })
                }
            }

            /***/
        }
        /******/
    ]
)
