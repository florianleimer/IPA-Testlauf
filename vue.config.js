const webpack = require('webpack');

module.exports = {
  devServer: {
    proxy: {
      '^/api/': {
        target: 'http://localhost:80/vue/IPA-Testlauf', // TODO: Change to your needs
        changeOrigin: true, // CORS verhindern
      }
    }
  },
  lintOnSave: 'warning',
  pwa: {
    name: 'Reporting-System',
    themeColor: '#344675',
    msTileColor: '#344675',
    appleMobileWebAppCapable: 'yes',
    appleMobileWebAppStatusBarStyle: '#344675'
  },
  css: {
    // Enable CSS source maps.
    sourceMap: process.env.NODE_ENV !== 'production'
  }
};
