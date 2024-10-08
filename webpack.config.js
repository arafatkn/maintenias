const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
  ...defaultConfig,
  devServer: {
    ...defaultConfig.devServer,
    allowedHosts: ["localhost:8881"],
  },
};
