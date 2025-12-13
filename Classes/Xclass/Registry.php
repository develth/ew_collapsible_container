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

use B13\Container\Events\BeforeContainerConfigurationIsAppliedEvent;
use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry as BaseRegistry;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

// @todo needed for $beforeContainerPreviewIsRendered
class Registry extends BaseRegistry
{
    /**
     * @param ContainerConfiguration $containerConfiguration
     */
    public function configureContainer(ContainerConfiguration $containerConfiguration): void
    {
        $beforeContainerConfigurationIsAppliedEvent = new BeforeContainerConfigurationIsAppliedEvent($containerConfiguration);
        $this->eventDispatcher->dispatch($beforeContainerConfigurationIsAppliedEvent);
        if ($beforeContainerConfigurationIsAppliedEvent->shouldBeSkipped()) {
            return;
        }
        if ((GeneralUtility::makeInstance(Typo3Version::class))->getMajorVersion() >= 12) {
            ExtensionManagementUtility::addTcaSelectItem(
                'tt_content',
                'CType',
                [
                    'label' => $containerConfiguration->getLabel(),
                    'value' => $containerConfiguration->getCType(),
                    'icon' => $containerConfiguration->getCType(),
                    'group' => $containerConfiguration->getGroup(),
                    'description' => $containerConfiguration->getDescription(),
                ]
            );
        } else {
            ExtensionManagementUtility::addTcaSelectItem(
                'tt_content',
                'CType',
                [
                    $containerConfiguration->getLabel(),
                    $containerConfiguration->getCType(),
                    $containerConfiguration->getCType(),
                    $containerConfiguration->getGroup(),
                ]
            );
        }
        $GLOBALS['TCA']['tt_content']['types'][$containerConfiguration->getCType()]['previewRenderer'] = ContainerPreviewRenderer::class;

        if ((GeneralUtility::makeInstance(Typo3Version::class))->getMajorVersion() >= 13) {
            if (!isset($GLOBALS['TCA']['tt_content']['types'][$containerConfiguration->getCType()]['creationOptions'])) {
                $GLOBALS['TCA']['tt_content']['types'][$containerConfiguration->getCType()]['creationOptions'] = [];
            }
            $GLOBALS['TCA']['tt_content']['types'][$containerConfiguration->getCType()]['creationOptions']['saveAndClose'] =
                $containerConfiguration->getSaveAndCloseInNewContentElementWizard();
        }
        foreach ($containerConfiguration->getGrid() as $row) {
            foreach ($row as $column) {
                if ((GeneralUtility::makeInstance(Typo3Version::class))->getMajorVersion() >= 12) {
                    $GLOBALS['TCA']['tt_content']['columns']['colPos']['config']['items'][] = [
                        'label' => $column['name'],
                        'value' => $column['colPos'],
                    ];
                } else {
                    $GLOBALS['TCA']['tt_content']['columns']['colPos']['config']['items'][] = [
                        $column['name'],
                        $column['colPos'],
                    ];
                }
            }
        }

        $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$containerConfiguration->getCType()] = $containerConfiguration->getCType();
        $GLOBALS['TCA']['tt_content']['types'][$containerConfiguration->getCType()]['showitem'] = '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header.ALT.div_formlabel,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                    --palette--;;appearanceLinks,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
';

        $GLOBALS['TCA']['tt_content']['containerConfiguration'][$containerConfiguration->getCType()] = $containerConfiguration->toArray();
    }
}
