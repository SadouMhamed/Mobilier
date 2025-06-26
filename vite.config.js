import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

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
});
