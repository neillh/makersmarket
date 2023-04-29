const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const SassLintPlugin = require('sass-lint-webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const production = process.env.NODE_ENV === 'production';
const assets = 'assets/src';
const dist = 'assets/dist';

process.traceDeprecation = true;

// Configuration object.
const config = {
	context: `${__dirname}/`,
	// Create the entry points.
	// One for frontend and one for the admin area.
	entry: {
		'makersmarket-home': ['@babel/polyfill', `./${assets}/js/home.js`, `./${assets}/scss/home.scss`],
	},

	// Create the output files.
	// One for each of our entry points.
	output: {
		path: path.resolve(__dirname, `${dist}`),
		publicPath: `/${dist}/`,
		filename: '[name].js'
	},

	// Setup a loader to transpile down the latest and great JavaScript so older browsers
	// can understand it.
	module: {
		rules: [
			{
				// Look for any .js files.
				test: /\.js$/,
				// Exclude the node_modules folder.
				exclude: /node_modules/,
				// Use babel loader to transpile the JS files.
				loader: 'babel-loader'
			},
			{
				test: /\.(s(a|c)ss)$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: { url: false }
					},
					'sass-loader'
				]
			},
			{
				//Images
				test: /\.(png|jpg|gif|svg)$/,
				loader: 'url-loader',
				options: {
					limit: 10000,
					name: `${dist}/img/[name].[ext]`
				}
			},
			{
				//Fonts
				test: /\.(eot|ttf|woff|woff2)$/,
				loader: 'url-loader',
				options: {
					limit: 10000,
					name: `${dist}/fonts/[name].[ext]`
				}
			}
		]
	},
	optimization: {
		minimize: true,
		// with this beautify, mangle and compress are false.
		minimizer: [new TerserPlugin({
			extractComments: false,
		})]
	},
	plugins: [
		new SassLintPlugin(),
		new MiniCssExtractPlugin({
			filename: '[name].css'
		}),
		new CopyWebpackPlugin({
			patterns: [
				{
					from: 'img/',
					to: 'img/',
					context: `${assets}/`,
				},
				//{from: `${assets}/fonts`, to: `fonts`},
				//{from: `${dist}/*.css`, to: `css/[name].css`},
				//{from: `${dist}/*.js`, to: `js/[name].js`}
			]
		})
	]
};

if (production) {
	config.plugins.push(
		new webpack.DefinePlugin({
			'process.env.NODE_ENV': JSON.stringify('production')
		})
	);
}

// Export the config object.
module.exports = config;
