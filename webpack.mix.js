const mix = require('laravel-mix');
const tailwind = require("tailwindcss");

require("laravel-mix-tailwind")
require("laravel-mix-purgecss")

mix
    .postCss("resources/css/app.css", "public/css", [
        tailwind("./tailwind.config.js"),
    ])
    .purgeCss();