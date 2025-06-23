import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/departamento.js',
                'resources/js/vendas.js',
                'resources/js/vendas-list.js',
                'resources/js/empregado.js'

            ],
            refresh: true,
        }),
    ],
});
