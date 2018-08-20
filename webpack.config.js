const ExtractTextPlugin = require('extract-text-webpack-plugin')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')
const resolve = file => path.resolve(__dirname, file)
const webpack = require('webpack')
const path = require('path')

const isProd = process.env.NODE_ENV === 'production'

const config = {
    entry: ['whatwg-fetch', 'string.prototype.includes', './src/main'],
    output: {
        path: resolve('./static'),
        filename: isProd ? 'bundle.js' : 'bundle.dev.js'
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        scss: ExtractTextPlugin.extract({
                            use: isProd
                                ? 'css-loader?minimize&url=false!sass-loader?minimize'
                                : 'css-loader?{"sourceMap":true}&url=false!sass-loader?{"sourceMap":true}',
                            fallback: 'vue-style-loader'
                        })
                    },
                    extractCSS: true,
                    preserveWhitespace: false,
                    postcss: [
                        require('autoprefixer')({
                            browsers: ['last 5 versions']
                        })
                    ]
                }
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.svg$/,
                loader: 'svg-inline-loader?removeSVGTagAttrs=false'
            },
            {
                test: /\.(png|woff|woff2|eot|ttf)$/,
                loader: 'url-loader',
                options: {
                    limit: 10000,
                    name: '[name].[ext]?[hash]'
                }
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    use: isProd
                        ? 'css-loader?minimize&url=false'
                        : 'css-loader?url=false',
                    fallback: 'vue-style-loader'
                })
            }
        ]
    },
    resolve: {
        modules: [
            path.resolve(__dirname, 'app'),
            resolve('./'),
            'node_modules'
        ],
        alias: {
            vue$: isProd ? 'vue/dist/vue.min.js' : 'vue/dist/vue.esm.js'
        }
    },
    performance: {
        maxEntrypointSize: 500000,
        maxAssetSize: 500000,
        hints: isProd ? 'warning' : false
    },
    plugins: [
        new ExtractTextPlugin({
            filename: 'bundle.css'
        }),
        new webpack.ProvidePlugin({
            _get: ['lodash/get']
        })
    ]
}

// production only
if (isProd) {
    config.plugins = config.plugins.concat([new UglifyJsPlugin()])

    // dev only
} else {
    config.devtool = '#source-map'
    config.plugins = config.plugins.concat([
        new webpack.EnvironmentPlugin({
            NODE_ENV: 'development'
        })
    ])
}

module.exports = config
