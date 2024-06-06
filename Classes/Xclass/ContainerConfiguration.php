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

use B13\Container\Tca\ContainerConfiguration as BaseContainerConfiguration;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContainerConfiguration extends BaseContainerConfiguration
{
    /**
     * @var string
     */
    protected $gridTemplate = 'EXT:ew_collapsible_container/Resources/Private/Templates/Container/Grid.html';

    public function __construct(
        string $cType,
        string $label,
        string $description,
        array $grid
    ) {
        parent::__construct($cType, $label, $description, $grid);
        if ((GeneralUtility::makeInstance(Typo3Version::class))->getMajorVersion() < 12) {
            $this->addGridPartialPath('EXT:ew_collapsible_container/Resources/Private/PartialsPre12/');
            $this->setGridTemplate(
                'EXT:ew_collapsible_container/Resources/Private/Templates/Container/GridPre12.html'
            );
        } else {
            $this->addGridPartialPath('EXT:ew_collapsible_container/Resources/Private/Partials/');
        }
    }
}
