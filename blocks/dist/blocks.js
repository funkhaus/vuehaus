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
            module.exports = __webpack_require__(2)

            /***/
        },
        /* 1 */
        /***/ function(module, exports) {
            var registerBlockType = wp.blocks.registerBlockType
            var RichText = wp.editor.RichText

            registerBlockType('custom-fh/block-slug', {
                title: 'Worker',
                description: 'The current block',
                icon: 'universal-access-alt',
                category: 'custom-fh',

                // This defines the shape of the data in the functions below
                attributes: {
                    content: {
                        type: 'array',
                        source: 'children',
                        selector: 'p'
                    }
                },

                // Editor
                edit: function edit(_ref) {
                    var attributes = _ref.attributes,
                        className = _ref.className,
                        setAttributes = _ref.setAttributes
                    var content = attributes.content

                    function onChangeContent(newContent) {
                        setAttributes({ content: newContent })
                    }

                    return wp.element.createElement(
                        'div',
                        { className: 'fh-custom-block' },
                        wp.element.createElement(RichText, {
                            tagName: 'p',
                            className: className,
                            onChange: onChangeContent,
                            value: content
                        })
                    )
                },

                // On save
                save: function save(_ref2) {
                    var attributes = _ref2.attributes,
                        className = _ref2.className
                    var content = attributes.content

                    return wp.element.createElement(RichText.Content, {
                        tagName: 'p',
                        className: className,
                        value: content
                    })
                }
            })

            /***/
        },
        /* 2 */
        /***/ function(module, exports) {
            // removed by extract-text-webpack-plugin
            /***/
        }
        /******/
    ]
)
