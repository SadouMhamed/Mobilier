import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.jsx",
                "resources/js/app-laravel.js",
            ],
            refresh: true,
        }),
        react(),
    ],
    esbuild: {
        jsx: "automatic",
        jsxImportSource: "react",
    },
    server: {
        host: "localhost",
        port: 5173,
        hmr: {
            host: "localhost",
        },
    },
    build: {
        manifest: "manifest.json", // Laravel expects manifest.json in build root
        outDir: "public/build",
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split(".");
                    const ext = info[info.length - 1];
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(ext)) {
                        return `assets/images/[name]-[hash][extname]`;
                    }
                    if (/css/i.test(ext)) {
                        return `assets/css/[name]-[hash][extname]`;
                    }
                    return `assets/[name]-[hash][extname]`;
                },
                chunkFileNames: "assets/js/[name]-[hash].js",
                entryFileNames: "assets/js/[name]-[hash].js",
            },
        },
    },
    define: {
        // Ensure HTTPS is used in production
        __VUE_PROD_DEVTOOLS__: false,
    },
});
