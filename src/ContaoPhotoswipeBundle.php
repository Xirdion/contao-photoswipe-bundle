<?php

declare(strict_types=1);

/*
 * This file is part of SowieSo contao-photoswipe-bundle
 *
 * @copyright  Copyright (c) 2022, Ideenwerkstatt Sowieso GmbH & Co. KG
 * @author     Sowieso GmbH & Co. KG <https://sowieso.team>
 * @link       https://github.com/sowieso-web/contao-photoswipe-bundle
 */

namespace Sowieso\PhotoswipeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoPhotoswipeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
