const ExtractTextPlugin = require('extract-text-webpack-plugin')
let webpack = require('webpack'),
    NODE_ENV = process.env.NODE_ENV || 'development',
    webpackConfig = {
        entry: ['./blocks/src/index.js', './blocks/src/_index.scss'],
        output: {
            path: __dirname,
            filename: './dist/blocks.js'
        },
        module: {
            loaders: [
                {
                    test: /.js$/,
                    loader: 'babel-loader',
                    exclude: /node_modules/
                },
                {
                    test: /.scss$/,
                    loader: ExtractTextPlugin.extract({
                        fallbackLoader: 'style-loader',
                        loader: 'css-loader!sass-loader'
                    })
                }
            ]
        },
        plugins: [
            new webpack.DefinePlugin({
                'process.env.NODE_ENV': JSON.stringify(NODE_ENV)
            }),
            new ExtractTextPlugin('./dist/blocks.css')
        ]
    }
if ('production' === NODE_ENV) {
    webpackConfig.plugins.push(new webpack.optimize.UglifyJsPlugin())
}

module.exports = webpackConfig
