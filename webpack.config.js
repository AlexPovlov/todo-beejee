const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

const production = process.env.NODE_ENV === "production";

module.exports = {
  entry: { app: path.resolve(__dirname, "./resources/js/index.js") },
  output: {
    path: path.resolve(__dirname, "./public"),
    filename: production ? "js/[name].[contenthash].js" : "js/[name].js",
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: ["babel-loader"],
      },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader',
        ],
      },
    ],
  },
  resolve: {
    extensions: ["*", ".js", ".jsx", ".scss", ".css"],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: production ? "css/[name].[contenthash].css" : "css/[name].css",
    }),
  ],
  devServer: {
    port: 3001,
    hot: true,
  },
  mode: production ? "production" : "development",
};
