const path = require( 'path' )
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' )
const resolve = file => path.resolve( __dirname, file )
const webpack = require( 'webpack' )

const config = {
    entry: './src/main',
    output: {
        path: resolve( 'static' ),
        filename: 'bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.vue$/,
                use: 'vue-loader'
            },
            {
                test: /\.svg$/,
                loader: 'svg-inline-loader?removeSVGTagAttrs=false'
            },
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract( {
                    fallback: 'style-loader',
                    use: [
                        { loader: 'css-loader' },
                        { loader: 'postcss-loader' }
                    ]
                } )
            }, {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel-loader'
            },
            {
                test: /\.(png|woff|woff2|eot|ttf|svg)$/,
                loader: 'url-loader?limit=100000'
            }
        ]
    },
    resolve: {
        modules: [
            path.resolve( __dirname, 'app' ),
            resolve( './' ),
            'node_modules'
        ],
        alias: {
            vue: 'vue/dist/vue.js',
            'replaceSVGs': resolve( 'src/libs/replaceSVGs.js' ),
            '_': 'lodash'
        }
    },
    plugins: [
        new ExtractTextPlugin( 'bundle.css' )
    ]
}

if ( process.env.NODE_ENV === 'production' ){

    // inject env variable
    config.plugins.push(
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            }
        })
    )

    // minify JS
    config.plugins.push(
        new webpack.optimize.UglifyJsPlugin( {
            compress: {
                warnings: false
            }
        } )
    )

    // minify CSS
    config.plugins.push(
        new OptimizeCssAssetsPlugin({
            cssProcessorOptions: { removeAllButFirst: true, zindex: false }
        })
    )

    config.plugins.push(new webpack.optimize.OccurrenceOrderPlugin())

    // use production version of vue
    config.resolve.alias.vue = 'vue/dist/vue.min.js'

} else {

    config.devtool = '#source-map'

}

module.exports = config
