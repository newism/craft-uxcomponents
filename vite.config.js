import {defineConfig} from 'vite'
import {resolve} from 'path';

export default defineConfig(({command}) => ({
    appType: 'mpa',
    root: resolve(__dirname, 'src/web/assets'),
    base: command === 'serve' ? '/' : '/dist/',
    build: {
        outDir: resolve(__dirname, 'src/web/assets/cp/dist'),
        emptyOutDir: true,
        manifest: true,
        sourcemap: false,
        rollupOptions: {
            input: resolve(__dirname, 'src/web/assets/cp/src/UxComponent.js'),
        },
    },
    server: {
        origin: 'http://localhost:4001',
    },
}));
