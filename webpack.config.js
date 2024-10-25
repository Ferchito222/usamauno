const webpack = require('webpack');
// const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const path = require('path');

module.exports = {
  // mode: 'development',
  mode: 'production',
  entry: {
    app: ['babel-polyfill', './resources/assets/js/app.js'],
    adm: './resources/assets/js/adm.js',
  },
  output: {
    path: path.resolve(__dirname, 'public/assets/js'),
    filename: '[name].js',
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['env', 'es2015', 'react', 'stage-2']
          }
        }
      },
      {
        test: /\.(css)$/,
        use: [
          {
            loader: "style-loader" // creates style nodes from JS strings
          }, {
            loader: "css-loader" // translates CSS into CommonJS
          }
        ]
      },
      {
        test: /\.less$/,
        use: [{
          loader: "style-loader" // creates style nodes from JS strings
        }, {
          loader: "css-loader" // translates CSS into CommonJS
        }, {
          loader: "less-loader" // compiles Less to CSS
        }]
      },
      {
        test: /\.styl$/,
        use: [{
          loader: "style-loader" // creates style nodes from JS strings
        }, {
          loader: "css-loader" // translates CSS into CommonJS
        }, {
          loader: "stylus-loader" // compiles Less to CSS
        }]
      },
      {
        test: /\.(png|woff|woff2|eot|ttf|svg)$/,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 100000
            }
          }
        ]
      },
    ]
  },
  performance: {
    hints: false,
  },
  plugins: [
    /* new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }), */
    // new ExtractTextPlugin('styles.css'),
    /* new UglifyJSPlugin({
      warningsFilter: (src) => true
    }) */
  ]
}