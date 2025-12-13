<?php

declare(strict_types=1);

/*
 * This file is developed by evoWeb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Evoweb\EwCollapsibleContainer\Xclass;

use B13\Container\Backend\Preview\ContainerPreviewRenderer as BaseContainerPreviewRenderer;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

// @todo needed for $beforeContainerPreviewIsRendered
#[Autoconfigure(public: true)]
class ContainerPreviewRenderer extends BaseContainerPreviewRenderer
{
    public function __construct(
        GridRenderer $gridRenderer,
        #[Autowire(service: 'cache.runtime')]
        FrontendInterface $runtimeCache
    ) {
        parent::__construct($gridRenderer, $runtimeCache);
    }
}
