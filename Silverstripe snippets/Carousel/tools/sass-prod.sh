#!/bin/bash

echo "Minifying CSS from CAROUSEL"
rm -rf ../www/css/sass/modules/_caroussel.sass
sass --update ../css/sass:../www/css/sass/modules --style compressed --no-cache --force