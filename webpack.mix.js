const mix = require('laravel-mix');
const webpack = require('webpack');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let revision = require('child_process')
    .execSync('git rev-parse --short HEAD')
    .toString()
    .trim()

mix
    .ts('resources/js/app.tsx', 'public/js')
    .react()
    .sourceMaps()
    .disableNotifications()
    .browserSync('127.0.0.1:8000')
    .webpackConfig({
        plugins: [
            new webpack.DefinePlugin({
                'process.env': JSON.stringify({
                    revision,
                    buildDate: new Date().toISOString()
                })
            })
        ]
    })
