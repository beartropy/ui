import * as esbuild from 'esbuild';

const isWatch = process.argv.includes('--watch');

const config = {
    entryPoints: ['resources/js/index.js'],
    bundle: true,
    outfile: 'resources/js/beartropy-ui.js',
    format: 'iife',
    platform: 'browser',
    target: 'es2020',
    minify: false,
    sourcemap: false,
    banner: {
        js: '// Beartropy UI - Bundled with esbuild\n',
    },
};

if (isWatch) {
    const ctx = await esbuild.context(config);
    await ctx.watch();
    console.log('👀 Watching for changes...');
} else {
    await esbuild.build(config);
    console.log('✅ Build complete!');
}
