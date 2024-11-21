import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    resolve: { // настроить предварительную компиляцию шаблонов Vue
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js'
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
            api: 'modern-compiler' // or "modern"
            }
        }
    }

});
