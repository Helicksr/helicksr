module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
    es2021: true
  },
  extends: [
    'plugin:vue/vue3-recommended',
    'standard-with-typescript',
    'prettier'
  ],
  overrides: [
  ],
  parser: "vue-eslint-parser",
  parserOptions: {
    parser: "@typescript-eslint/parser",
    ecmaVersion: 'latest',
    sourceType: 'module',
    project: 'tsconfig.json',
    extraFileExtensions: ['.vue'],
  },
  plugins: [
    'vue'
  ],
  rules: {
  },
  ignorePatterns: ['vite.config.ts'],
}
