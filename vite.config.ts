import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
  resolve: {
    alias: {
      "~~": path.resolve(__dirname, "./resources/ts"),
      "~~/*": path.resolve(__dirname, "./resources/ts/*"),
      "@@": path.resolve(__dirname, "./resources/ts"),
      "@@/*": path.resolve(__dirname, "./resources/ts/*"),
      "~": path.resolve(__dirname, "./resources/ts"),
      "~/*": path.resolve(__dirname, "./resources/ts/*"),
      "@": path.resolve(__dirname, "./resources/ts"),
      "@/*": path.resolve(__dirname, "./resources/ts/*"),
      "public": path.resolve(__dirname, "./public"),
    },
  },
  plugins: [
    laravel({
      input: 'resources/ts/app.ts',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
});
