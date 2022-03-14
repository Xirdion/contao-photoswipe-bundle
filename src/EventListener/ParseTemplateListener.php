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

use Contao\Template;
use Sowieso\PhotoswipeBundle\Photoswipe\Photoswipe;

class ParseTemplateListener
{
    public function __construct(
        private Photoswipe $photoswipe,
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

        $templateName = $template->getName();

        // Contao template for a single image
        if (true === str_starts_with($templateName, 'image')) {
            $this->handleImageTemplate($template);

            return;
        }

        // Contao template for a image gallery
        if (true === str_starts_with($templateName, 'gallery')) {
            $this->handleGalleryTemplate($template);
        }
    }

    private function handleGalleryTemplate(Template $template): void
    {
        if (true !== (bool) $template->__get('fullsize')) {
            return;
        }

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

        // Generate unique photoswipe selector
        $psSelector = 'pswp__container--' . $template->__get('id');
        $this->photoswipe->addElement($psSelector);

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
        $psSelector = $this->generatePhotoswipeSelector($template);

        // Add additional unique photoswipe class to the image container (figure)
        $containerClass = (string) $template->__get('floatClass');
        $containerClass .= ' ' . $psSelector;
        $template->__set('floatClass', $containerClass);

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

        $additionalData = [
            'src' => $lightBoxData['img']['src'],
            'width' => $lightBoxData['img']['width'],
            'height' => $lightBoxData['img']['height'],
            'cropped' => '1',
            'caption' => $templateData['caption'] ?? '',
        ];

        // Add additional attributes to the anchor-tag
        $attributes = $templateData['attributes'] ?? '';
        foreach ($additionalData as $attr => $value) {
            $attributes .= ' data-pswp-' . $attr . '="' . $value . '"';
        }
        $templateData['attributes'] = $attributes;

        return $templateData;
    }

    /**
     * @param Template $template
     *
     * @return string
     */
    private function generatePhotoswipeSelector(Template $template): string
    {
        $psSelector = 'pswp__container--' . $template->__get('id');
        $this->photoswipe->addElement($psSelector);

        return $psSelector;
    }
}
