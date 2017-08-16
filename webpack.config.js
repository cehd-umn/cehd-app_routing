const path = require("path");
const webpack = require("webpack");

module.exports = {
     entry: "./public/assets/src/js/app.js",
     output: {
         path: path.resolve(__dirname, "public/assets/dist/js"),
         filename: "cehd.min.js"
     },
     module: {
         loaders: [{
             test: /\.js$/,
             exclude: /node_modules/,
             loader: "babel-loader"
         }]
     },
     plugins: [
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false,
            },
            output: {
                comments: false,
            },
        }),
    ]
 };

// module.rules = [{
//     test: require.resolve("jquery"),
//     use: [{
//             loader: "expose-loader",
//             options: "$"
//         },
//     ]
// }];
