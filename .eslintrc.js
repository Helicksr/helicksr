module.exports = {
    root: true,
    env: {
        browser: true,
        node: true,
        es2021: true,
    },
    extends: [
        'plugin:vue/vue3-recommended',
        'standard-with-typescript',
        'prettier',
    ],
    overrides: [],
    parser: 'vue-eslint-parser',
    parserOptions: {
        parser: '@typescript-eslint/parser',
        ecmaVersion: 'latest',
        sourceType: 'module',
        project: 'tsconfig.json',
        extraFileExtensions: ['.vue'],
    },
    plugins: ['vue'],
    rules: {
        'vue/multi-word-component-names': [
            'error',
            {
                // ignoring default pages components
                ignores: [
                    'Create',
                    'Edit',
                    'Index',
                    'Login',
                    'Register',
                    'Show',
                ],
            },
        ],
    },
    ignorePatterns: ['vite.config.ts'],
};
