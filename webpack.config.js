var webpack = require("webpack");

module.exports = {
    entry: "/Users/dima/www/godesigner/app/src/pages/main.jsx",
    output: {
        path: "/Users/dima/www/godesigner/app/webroot/js/pages/",
        filename: "main.js"
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel?presets[]=react,presets[]=es2015'
            }
        ]
    }/*,
    plugins: [
        //new webpack.optimize.UglifyJsPlugin({minimize: true})
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        })
    ]*/
    /*resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    }*/
};