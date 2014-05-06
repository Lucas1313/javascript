#!/bin/bash

rm ../css/dev/*
echo "Watching SASS for CAROUSSEL"
sass --watch ../css/sass:../css/dev --style expanded --no-cache --line-numbers
