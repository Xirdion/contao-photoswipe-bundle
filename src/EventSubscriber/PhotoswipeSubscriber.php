<?php

declare(strict_types=1);

/*
 * This file is part of SowieSo contao-photoswipe-bundle
 *
 * @copyright  Copyright (c) 2022, Ideenwerkstatt Sowieso GmbH & Co. KG
 * @author     Sowieso GmbH & Co. KG <https://sowieso.team>
 * @link       https://github.com/sowieso-web/contao-photoswipe-bundle
 */

namespace Sowieso\PhotoswipeBundle\EventSubscriber;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Sowieso\PhotoswipeBundle\Photoswipe\Photoswipe;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PhotoswipeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ScopeMatcher $scopeMatcher,
        private Photoswipe $photoswipe,
    ) {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'addPhotoswipeResources',
        ];
    }

    public function addPhotoswipeResources(ResponseEvent $event): void
    {
        // Check if the current request is the main frontend request of contao
        // Only this request needs the modifications
        if (false === $this->scopeMatcher->isFrontendMainRequest($event)) {
            return;
        }

        if (false === $this->photoswipe->hasElements()) {
            return;
        }

        // Get the current response content
        $response = $event->getResponse();
        $content = $response->getContent();
        if (false === $content) {
            return;
        }

        $content = $this->addPhotoswipeStyling($content);
        $content = $this->addPhotoswipeJavaScript($content);

        $response->setContent($content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function addPhotoswipeStyling(string $content): string
    {
        // Check if the correct position in the html could be found
        $headPos = strripos($content, '</head>');
        if (false === $headPos) {
            return $content;
        }

        // Adding the photoswipe CSS styling
        $psStyleLink = '<link rel="stylesheet" href="/bundles/contaophotoswipe/photoswipe.min.css"/>';

        return substr($content, 0, $headPos) . $psStyleLink . substr($content, $headPos);
    }

    /**
     * Add the script-tag and the photoswipe config to the webpage.
     *
     * @param string $content
     *
     * @return string
     */
    private function addPhotoswipeJavaScript(string $content): string
    {
        // Check if the correct position in the html could be found
        $bodyPos = strripos($content, '</body>');
        if (false === $bodyPos) {
            return $content;
        }

        $lightbox = <<<'LIGHTBOX'
            options = {
                gallery: '.%s',
                children: 'a',
                pswpModule: '/bundles/contaophotoswipe/photoswipe.esm.min.js'
            };
            lightbox = new PhotoSwipeLightbox(options);

            lightbox.init();
            LIGHTBOX;

        $lightboxes = '';
        $elements = $this->photoswipe->getElements();
        foreach ($elements as $element) {
            $lightboxes .= sprintf($lightbox, $element);
        }

        // Adding the photoswipe JavaScript
        $psJs = <<<'PHOTOSWIPE'
            <script type="module">
            // Include Lightbox
            import PhotoSwipeLightbox from "/bundles/contaophotoswipe/photoswipe-lightbox.esm.min.js";

            let options;
            let lightbox;
            %s
            </script>
            PHOTOSWIPE;

        $psJs = sprintf($psJs, $lightboxes);

        return substr($content, 0, $bodyPos) . $psJs . substr($content, $bodyPos);
    }
}
