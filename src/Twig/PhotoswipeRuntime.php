<?php

declare(strict_types=1);

/*
 * This file is part of SowieSo contao-photoswipe-bundle
 *
 * @copyright  Copyright (c) 2022, Ideenwerkstatt Sowieso GmbH & Co. KG
 * @author     Sowieso GmbH & Co. KG <https://sowieso.team>
 * @link       https://github.com/sowieso-web/contao-photoswipe-bundle
 */

namespace Sowieso\PhotoswipeBundle\Twig;

use Contao\CoreBundle\Image\Studio\Figure;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\String\HtmlAttributes;
use Sowieso\PhotoswipeBundle\Photoswipe\PhotoswipeList;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class PhotoswipeRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly PhotoswipeList $photoswipeList,
    ) {
    }

    /**
     * An additional HTML class is added to the HTML-attributes.
     *
     * @return HtmlAttributes|string
     */
    public function getContainerClass(): HtmlAttributes|string
    {
        // Initialize the HtmlAttributes
        $attributes = class_exists(HtmlAttributes::class) ? new HtmlAttributes() : '';

        // Check if the extra class should get added
        if (false === $this->checkConditions()) {
            return $attributes;
        }

        // Add the additional container class
        if ($attributes instanceof HtmlAttributes) {
            $attributes->set('class', 'contao-pswp__container--' . $this->photoswipeList->increaseCounter());
        } else {
            $attributes = 'class="contao-pswp__container--' . $this->photoswipeList->increaseCounter() . '"';
        }

        return $attributes;
    }

    /**
     * Additional parameters are generated and added.
     * These parameters are meant to be used for anchor-tags of images.
     * Following parameters are added:
     * class: pswp__item
     * data-attributes: pswp-src, pswp-width, pswp-height.
     *
     * @param Figure|null $figure
     *
     * @return HtmlAttributes|string
     */
    public function getAttributes(Figure|null $figure): HtmlAttributes|string
    {
        // Initialize the HtmlAttributes
        $attributes = class_exists(HtmlAttributes::class) ? new HtmlAttributes() : '';

        // Check if a figure is given
        if (null === $figure) {
            return $attributes;
        }

        // Run some additional checks
        if (false === $this->checkConditions()) {
            return $attributes;
        }

        // Check if a lightbox should be generated
        if (false === $figure->hasLightbox()) {
            return $attributes;
        }

        // Add a new element to the list
        if (false === $this->photoswipeList->hasEntry($this->photoswipeList->getCounter())) {
            $this->photoswipeList->addElement();
        }

        // Add the additional parameters
        $image = $figure->getLightbox()->getImage();
        $size = $image->getOriginalDimensions()->getSize();

        // Depending on the current Contao version HtmlAttributes can be used or not.
        if ($attributes instanceof HtmlAttributes) {
            $attributes->set('class', 'contao-pswp__item');
            $attributes->set('data-pswp-src', $image->getImageSrc());
            $attributes->set('data-pswp-width', $size->getWidth());
            $attributes->set('data-pswp-height', $size->getHeight());
        } else {
            $attributes = 'class="contao-pswp__item"'
                . 'data-pswp-src="' . $image->getImageSrc() . '"'
                . 'data-pswp-width="' . $size->getWidth() . '"'
                . 'data-pswp-height="' . $size->getHeight() . '"'
            ;
        }

        return $attributes;
    }

    /**
     * Check if all conditions for processing the request are met.
     *
     * @return bool
     */
    private function checkConditions(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return false;
        }

        if (false === $this->scopeMatcher->isFrontendRequest($request)) {
            return false;
        }

        return true;
    }
}
