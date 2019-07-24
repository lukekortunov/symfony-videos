'use strict';

const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addStyleEntry('css/styles', './assets/css/styles.css')
    .addStyleEntry('css/dashboard', './assets/css/dashboard.css')
    .addStyleEntry('css/login', './assets/css/login.css')
    .addStyleEntry('css/fontawesome', './assets/css/fontawesome.min.css')
    .enableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
