const path = require('path')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const resolve = file => path.resolve(__dirname, file)
const webpack = require('webpack')

const config = {
    entry: './src/main',
    output: {
        path: './static',
        filename: 'bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.vue$/,
                use: 'vue-loader'
            },
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract({
                    fallbackLoader: 'style-loader',
                    loader: [
                        { loader: 'css-loader' },
                        { loader: 'postcss-loader' }
                    ]
                })
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
            path.resolve(__dirname, 'app'),
            resolve('./'),
            'node_modules'
        ],
        alias: {
            vue: 'vue/dist/vue.js',
            'replaceSVGs': resolve('src/libs/replaceSVGs.js')
        }
    },
    plugins: [
        new ExtractTextPlugin('bundle.css')
    ]
}

if ( process.env.NODE_ENV === 'production' ) {

    // add these plugins for production mode
    config.plugins = config.plugins.concat([
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        }),
        new webpack.optimize.OccurenceOrderPlugin()
    ])

} else {

    config.devtool = '#source-map'

}

module.exports = config
