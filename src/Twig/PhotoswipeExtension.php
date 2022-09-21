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

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PhotoswipeExtension extends AbstractExtension
{
    /**
     * Adding new twig function "pswp_attr".
     * This function will calculate extra attributes for Contao\CoreBundle\Image\Studio\Figure elements.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pswp_container_class', [PhotoswipeRuntime::class, 'getContainerClass']),
            new TwigFunction('pswp_attr', [PhotoswipeRuntime::class, 'getAttributes']),
        ];
    }
}
