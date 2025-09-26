import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/main.js',
                'resources/js/login.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        assetsDir: 'assets',
        rollupOptions: {
            output: {
                entryFileNames: 'assets/js/[name].js',
                chunkFileNames: 'assets/js/[name].js',
                assetFileNames: (assetInfo) => {
                    const ext = assetInfo.name.split('.').pop();

                    if (ext === 'css') {
                        return 'assets/css/[name].[ext]';
                    }

                    if (['js', 'mjs'].includes(ext)) {
                        return 'assets/js/[name].[ext]';
                    }

                    return 'assets/[name].[ext]';
                }
            }
        }
    }
});
