const path = require('path');
const webpack = require('webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const WriteFilePlugin = require('write-file-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const fs = require('fs');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");


function generateHtmlPlugins() {
  const viewsDir = './src/views';
  const views = fs.readdirSync(path.resolve(__dirname, viewsDir));
  const htmlPlugins = [];

  for (const view of views) {
    if (view) {
      const fileParts = view.split('.');
      const name = fileParts[0];
      const extension = fileParts[1];

      if (name && extension) {
        const config = {
          template: path.resolve(__dirname, `${viewsDir}/${view}`),
          minify: true,
          hash: true,
          alwaysWriteToDisk: true
        };

        if (name && name !== 'index') {
          config.filename = `${name}.html`;
        }

        htmlPlugins.push(new HtmlWebpackPlugin(config));
      }
    }
  }

  return htmlPlugins;
}

const htmlPlugins = generateHtmlPlugins();

module.exports = {
  entry: {
    main: './src/js/main.js'
  },

  output: {
    path: path.resolve(__dirname + '/dist'),
    filename: '[name].js',
    library: 'TD'
  },

  module: {
    rules: [
      {
        test: /\.html$/,
        use: {
          loader: 'html-loader',
          options: {
            interpolate: true
          }
        }
      },
      {
        test: /\.scss$/,
        use: [
          'style-loader',
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader'
        ],
      },
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              ['@babel/preset-env']
            ]
          }
        }
      },
      {
        test: /\.(gif|png|jpe?g|svg)$/i,
        use: [
          'file-loader',
          {
            loader: 'image-webpack-loader',
            options: {
              disable: true
            },
          },
        ],
      },
      {
        test: /\.(woff(2)?|ttf|otf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [{
          loader: 'file-loader',
          options: {
            name: '[name].[ext]',
            outputPath: 'fonts/'
          }
        }]
      }
    ],
  },

  devtool: 'eval-source-map',

  devServer: {
    host: 'localhost',
    port: 3000,
    stats: 'normal',
    open: true
  },


  plugins: [
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery'
    }),
    new CleanWebpackPlugin(['dist'], {}),
    new MiniCssExtractPlugin({
      filename: "styles.css"
    }),
  ].concat(htmlPlugins)
    .concat([
      new WriteFilePlugin()
    ]),
};
