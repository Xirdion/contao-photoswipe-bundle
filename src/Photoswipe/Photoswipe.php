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

class Photoswipe
{
    /**
     * @var array<int, string>
     */
    private static array $elements = [];

    /**
     * @return array<int, string>
     */
    public function getElements(): array
    {
        return self::$elements;
    }

    public function addElement(string $element): void
    {
        self::$elements[] = $element;
    }

    public function hasElements(): bool
    {
        return \count(self::$elements) > 0;
    }
}
