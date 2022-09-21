<?php

declare(strict_types=1);

/*
 * This file is part of SowieSo contao-photoswipe-bundle
 *
 * @copyright  Copyright (c) 2022, Ideenwerkstatt Sowieso GmbH & Co. KG
 * @author     Sowieso GmbH & Co. KG <https://sowieso.team>
 * @link       https://github.com/sowieso-web/contao-photoswipe-bundle
 */

namespace Sowieso\PhotoswipeBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Template;
use Sowieso\PhotoswipeBundle\Photoswipe\PhotoswipeList;
use Symfony\Component\HttpFoundation\RequestStack;

class ParseTemplateListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly PhotoswipeList $photoswipeList,
    ) {
    }

    /**
     * Add some photoswipe specific markup to the template object.
     *
     * @param Template $template
     *
     * @return void
     */
    public function onParseTemplate(Template $template): void
    {
        // Check if it is a fully functional template
        if (null === $template->__get('id')) {
            return;
        }

        // Check the current request
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }

        if (false === $this->scopeMatcher->isFrontendRequest($request)) {
            return;
        }

        // Only handle specific templates
        $templateName = $template->getName();

        // Contao template for a single image
        if (true === str_starts_with($templateName, 'image')) {
            $this->handleImageTemplate($template);

            return;
        }

        // Contao template for an image gallery
        if (true === str_starts_with($templateName, 'gallery')) {
            $this->handleGalleryTemplate($template);
        }
    }

    /**
     * @param Template $template
     *
     * @return void
     */
    private function handleGalleryTemplate(Template $template): void
    {
        if (true !== (bool) $template->__get('fullsize')) {
            return;
        }

        // Generate unique photoswipe selector
        $psSelector = $this->generatePhotoswipeSelector();

        $rows = (array) $template->__get('body');
        foreach ($rows as $rowI => $row) {
            foreach ($row as $colI => $col) {
                if (false === $col->addImage) {
                    continue;
                }

                try {
                    $data = json_decode(
                        (string) json_encode($col, \JSON_THROW_ON_ERROR),
                        true,
                        512,
                        \JSON_THROW_ON_ERROR
                    );
                    $imageData = $this->modifyTemplateData($data);
                } catch (\JsonException $e) {
                    $imageData = [];
                }
                foreach ($imageData as $field => $data) {
                    $col->{$field} = $data;
                }

                $rows[$rowI][$colI] = $col;
            }
        }
        $template->__set('body', $rows);

        // Add additional unique photoswipe class to the gallery container (ul)
        $containerClass = (string) $template->__get('perRow');
        $template->__set('perRow', $containerClass . ' ' . $psSelector);
    }

    private function handleImageTemplate(Template $template): void
    {
        if (true !== $template->__get('fullsize')) {
            return;
        }

        // Generate unique photoswipe selector
        $psSelector = $this->generatePhotoswipeSelector();

        // Add additional unique photoswipe class to the image container (figure)
        $containerClass = (string) $template->__get('floatClass');
        $template->__set('floatClass', $containerClass . ' ' . $psSelector);

        $template->setData($this->modifyTemplateData($template->getData()));
    }

    /**
     * @param array $templateData
     *
     * @return array
     *
     * @phpstan-ignore-next-line
     */
    private function modifyTemplateData(array $templateData): array
    {
        $lightBoxData = $templateData['lightboxPicture'] ?? null;
        if (null === $lightBoxData) {
            return $templateData;
        }

        if (false === $this->photoswipeList->hasEntry($this->photoswipeList->getCounter())) {
            $this->photoswipeList->addElement();
        }

        $additionalData = [
            'src' => $lightBoxData['img']['src'],
            'width' => $lightBoxData['img']['width'],
            'height' => $lightBoxData['img']['height'],
        ];

        // different HTML attributes for the image anchor tag
        $attributes = $templateData['attributes'] ?? '';

        // Extend the class of the anchor tag
        $linkClass = $this->extractAttributeProperty($attributes, 'class');
        if (null === $linkClass) {
            $attributes .= ' class="contao-pswp__item"';
        } else {
            $attributes = str_replace($linkClass, 'contao-pswp__item ' . $linkClass, $attributes);
        }

        // Add additional attributes to the anchor tag
        foreach ($additionalData as $attr => $value) {
            $attributes .= ' data-pswp-' . $attr . '="' . $value . '"';
        }

        $templateData['attributes'] = $attributes;

        return $templateData;
    }

    /**
     * @return string
     */
    private function generatePhotoswipeSelector(): string
    {
        // Return the unique photoswipe class
        return 'contao-pswp__container--' . $this->photoswipeList->increaseCounter();
    }

    /**
     * Try to extract a given property from a attribute string.
     *
     * @param string  $attributes
     * @param ?string $prop
     *
     * @return string|null
     */
    private function extractAttributeProperty(string $attributes, ?string $prop): ?string
    {
        if ('' === $attributes || !$prop) {
            return null;
        }

        $attrEntries = explode(' ', $attributes);
        foreach ($attrEntries as $attribute) {
            if ('' === $attribute) {
                continue;
            }
            // Check if there is a lightbox attribute from Contao
            if (false === str_starts_with($attribute, $prop . '="')) {
                continue;
            }

            // Check if the lightbox-attribute is not just empty
            if ($prop . '=""' === $attribute) {
                continue;
            }

            return explode('"', $attribute)[1];
        }

        return null;
    }
}
