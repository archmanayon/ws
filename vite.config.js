import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        larave([
            'resources/js/app.js',
        ]),
    ],
});
