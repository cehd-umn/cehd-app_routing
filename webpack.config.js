const path = require("path");
const UglifyJSPlugin = require("uglifyjs-webpack-plugin");
const webpack = require("webpack");

module.exports = {
    entry: {
        app: "./public/assets/src/js/app.js",
        sorter: "./public/assets/src/js/sorter.js"
    },
    output: {
        path: path.resolve(__dirname, "public/assets/dist/js"),
        filename: "[name].min.js"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use:
                {
                    loader: "babel-loader",
                    options: {
                        presets: ["env"]
                    }
                }
            },
            {
                test: require.resolve("jquery"),
                use:
                [
                    {
                        loader: "expose-loader",
                        options: "$"
                    }
                ],
            }
        ],
    },
    plugins: [
        new UglifyJSPlugin({
            compress: {
                warnings: false,
            },
            output: {
                comments: false,
            },
            sourceMap: false
        }),
        new webpack.optimize.CommonsChunkPlugin({
            name: "common"
        }),
    ]
};
