import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        })
    ],
    css: {
        preprocessorOptions: {
          scss: {
            additionalData: `@import "@/styles/var.scss";`
          }
        }
      },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '127.0.0.1',
        }
    }
});
