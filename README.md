# Contao Photoswipe Bundle

This bundle implements [Photoswipe.js](https://photoswipe.com/v5/docs/getting-started/) as lightbox for images and galleries.

Additional HTML markup is added dynamically while parsing the templates:
- At the moment only `image` and `gallery` templates are taken into account
- A **unique photoswipe-gallery-class** (`pswp__container--<template-ID>`) is generated and added to following template-properties:
    - `floatClass` for image templates
    - `row` for gallery templates
- The `data-pswp-src` is added to the image anchor tag
- The dimensions of the original image are added with `data-pswp-width` and `data-pswp-height` to the image tag

**The additional CSS and JS resources are added to the response only if there are any unique photoswipe selectors**
