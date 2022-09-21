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
    private PhotoswipeConfig $config;

    /**
     * @param int                  $id
     * @param array<string, mixed> $configData
     */
    public function __construct(
        private readonly int $id,
        array $configData,
    ) {
        $this->config = new PhotoswipeConfig();
        $this->config->createConfigFromArray($configData);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return PhotoswipeConfig
     */
    public function getConfig(): PhotoswipeConfig
    {
        return $this->config;
    }
}
