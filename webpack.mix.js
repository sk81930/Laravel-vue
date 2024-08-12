// webpack.mix.js

const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue({
        version: 3,
        options: {
            compilerOptions: {
              isCustomElement: (tag) => ['md-linedivider'].includes(tag),
            },
        },
    })
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);