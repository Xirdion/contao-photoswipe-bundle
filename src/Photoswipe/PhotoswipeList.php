<?php

declare(strict_types=1);

/*
 * This file is part of SowieSo contao-photoswipe-bundle
 *
 * @copyright  Copyright (c) 2022, Ideenwerkstatt Sowieso GmbH & Co. KG
 * @author     Sowieso GmbH & Co. KG <https://sowieso.team>
 * @link       https://github.com/sowieso-web/contao-photoswipe-bundle
 */

namespace Sowieso\PhotoswipeBundle\Photoswipe;

class PhotoswipeList
{
    /**
     * @var array<string, Photoswipe>
     */
    private static array $elements = [];

    /**
     * @return array<string, Photoswipe>
     */
    public function getElements(): array
    {
        return self::$elements;
    }

    public function addElement(Photoswipe $element): void
    {
        // Do not add a duplicate element
        if (true === \array_key_exists($element->getSelector(), self::$elements)) {
            return;
        }

        self::$elements[$element->getSelector()] = $element;
    }

    public function hasElements(): bool
    {
        return \count(self::$elements) > 0;
    }
}
