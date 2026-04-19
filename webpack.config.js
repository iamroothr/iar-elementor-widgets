const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: {
        heroWidget: './resources/scripts/hero-widget/app.js',
        heroSliderWidget: './resources/scripts/hero-slider-widget/app.js',
        imageGridWidget: './resources/scripts/image-grid-widget/app.js',
    },
    output: {
        filename: '[name]/bundle.js',
        path: path.resolve(__dirname, 'public')
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader',
                ],
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name]/style.css',
        }),
    ],
    mode: 'development'
};
