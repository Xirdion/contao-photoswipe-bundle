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
    private static int $counter = 0;

    /**
     * @var array<int, Photoswipe>
     */
    private static array $elements = [];

    public function getCounter(): int
    {
        return self::$counter;
    }

    public function increaseCounter(): int
    {
        return ++self::$counter;
    }

    /**
     * @return array<int, Photoswipe>
     */
    public function getElements(): array
    {
        return self::$elements;
    }

    /**
     * Try to add a PhotoSwipe element with its config to the list.
     *
     * @return void
     */
    public function addElement(): void
    {
        // Add the element to the list
        self::$elements[self::$counter] = new Photoswipe(self::$counter, ['caption' => true]);
    }

    public function hasEntry(int $id): bool
    {
        return \array_key_exists($id, self::$elements);
    }

    public function hasElements(): bool
    {
        return \count(self::$elements) > 0;
    }
}
