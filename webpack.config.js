const ExtractTextPlugin = require( 'extract-text-webpack-plugin' )
const resolve = file => path.resolve( __dirname, file )
const webpack = require( 'webpack' )
const path = require( 'path' )

const isProd = process.env.NODE_ENV === 'production'

const devPlugins = [
    new webpack.EnvironmentPlugin({
        NODE_ENV: 'development'
    }),
    new ExtractTextPlugin({
        filename: 'bundle.css'
    })
]
const prodPlugins = devPlugins.concat([
    new webpack.optimize.UglifyJsPlugin({
        compress: { warnings: false }
    }),
    new ExtractTextPlugin({
        filename: 'bundle.css'
    })
])

const config = {
    entry: ['whatwg-fetch', './src/main'],
    output: {
        path: resolve( 'static' ),
        filename: isProd ? 'bundle.js' : 'bundle.dev.js'
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        'scss': ExtractTextPlugin.extract({
                            use: isProd ? 'css-loader?minimize!sass-loader?minimize' : 'css-loader!sass-loader',
                            fallback: 'vue-style-loader'
                        })
                    },
                    extractCSS: true,
                    preserveWhitespace: !isProd,
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
                    use: isProd ? 'css-loader?minimize' : 'css-loader',
                    fallback: 'vue-style-loader'
                })
            }
        ]
    },
    resolve: {
        modules: [
            path.resolve( __dirname, 'app' ),
            resolve( './' ),
            'node_modules'
        ]
    },
    performance: {
        maxEntrypointSize: 700000,
        hints: isProd ? 'warning' : false
    },
    plugins: isProd ? prodPlugins : devPlugins
}

if (!isProd) config.devtool = '#source-map'

module.exports = config
