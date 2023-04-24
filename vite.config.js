import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import {defineConfig, loadEnv} from 'vite'

export default ({ mode }) => {

  // Load app-level env vars to node-level env vars.
  process.env = {...process.env, ...loadEnv(mode, process.cwd())};

  const homedir = require("os").homedir()
  const domain = process.env.VITE_DOMAIN

  return defineConfig({
    plugins: [
      vue(),
      laravel([
        'resources/js/app.js',
      ]),
    ],
    server: {
      https: {
        key: homedir + "/.config/valet/Certificates/" + domain + ".key",
        cert: homedir + "/.config/valet/Certificates/" + domain + ".crt",
      },
      host: domain,
      hmr: {
        host: domain,
      },
    },
  });
}

