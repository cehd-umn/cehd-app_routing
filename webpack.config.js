const path = require("path");
const UglifyJSPlugin = require("uglifyjs-webpack-plugin");

module.exports = {
    entry: "./public/assets/src/js/app.js",
    output: {
        path: path.resolve(__dirname, "public/assets/dist/js"),
        filename: "cehd.min.js"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                        loader: "babel-loader",
                        options: {
                        presets: ["env"]
                        }
                    }
            },
            {
                test: require.resolve("jquery"),
                use: [{
                loader: "expose-loader",
                options: "$"
                }],
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
        sourceMap: true
    })]
};
