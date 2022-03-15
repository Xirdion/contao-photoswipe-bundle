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

class PhotoswipeConfig
{
    private bool $showCaption;

    /**
     * @param array<string, bool> $data
     *
     * @return void
     */
    public function createConfigFromArray(array $data): void
    {
        foreach ($data as $prop => $value) {
            switch ($prop) {
                case 'caption':
                    $this->showCaption = $value;
                    break;
            }
        }
    }

    /**
     * @return bool
     */
    public function isShowCaption(): bool
    {
        return $this->showCaption;
    }
}
