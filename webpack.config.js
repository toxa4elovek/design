var webpack = require("webpack");
var path = require('path');
ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: "C:\\server\\www\\godesigner\\app\\src\\components.jsx",
    output: {
        path: "C:\\server\\www\\godesigner\\app\\webroot\\dist\\",
        filename: "components.js"
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
            },
            {
                test: /\.(jpe?g|png|gif|svg)$/i,
                loaders: [
                    'url-loader?limit=10000&hash=sha512&digest=hex&name=[hash].[ext]',
                    'image-webpack?bypassOnDebug&optimizationLevel=7&interlaced=false'
                ]
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
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': '"production"'
            }
        }),
        new ExtractTextPlugin('components.css', {
            allChunks: true
        }),
        new webpack.optimize.UglifyJsPlugin({minimize: true})
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