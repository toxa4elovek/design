var webpack = require("webpack");
var path = require('path');
ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: "/Users/dima/www/godesigner/app/src/pages/main.jsx",
    output: {
        path: "/Users/dima/www/godesigner/app/webroot/dist/",
        filename: "main.js"
    },
    module: {
        loaders: [
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract('style', 'css?modules&importLoaders=1&localIdentName=[name]__[local]___[hash:base64:5]!postcss-loader')
            },
            {
                test: /\.jsx?$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel?presets[]=react,presets[]=es2015'
            }
        ]
    },
    postcss: function () {
        return [
            require('autoprefixer'),
            require('postcss-autoreset'),
            //require('cssnano'),
        ];
    },
    plugins: [
        new ExtractTextPlugin('build.css', {
            allChunks: true
        })
        //new webpack.optimize.UglifyJsPlugin({minimize: true})
        /*new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        })*/
    ]
    /*resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    }*/
};