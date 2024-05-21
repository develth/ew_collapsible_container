<?php

declare(strict_types=1);

namespace Evoweb\EwCollapsibleContainer\Xclass;

/*
 * This file is part of TYPO3 CMS-based extension "ew_collapsible_container" by evoweb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Container\Backend\Grid\ContainerGridColumn as BaseContainerGridColumn;
use B13\Container\Domain\Model\Container;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\View\PageLayoutContext;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContainerGridColumn extends BaseContainerGridColumn
{
    public function __construct(
        PageLayoutContext $context,
        array $columnDefinition,
        Container $container,
        int $newContentElementAtTopTarget,
        bool $allowNewContentElements = true,
        protected bool $collapsed = false,
        protected int $minitems = 0
    ) {
        parent::__construct(
            $context,
            $columnDefinition,
            $container,
            $newContentElementAtTopTarget,
            $allowNewContentElements
        );
    }

    public function getCollapsed(): bool
    {
        return $this->collapsed;
    }

    public function getChildAllowedTypesCount(): int
    {
        if (!($this->definition['allowDirectNewLink'] ?? false)) {
            return PHP_INT_MAX;
        }

        $allowed = $this->definition['allowed'] ?? [];
        $cType = explode(',', $allowed['CType'] ?? '');
        $listType = explode(',', $allowed['list_type'] ?? '');

        return count($cType)
            // only add list type count if list is in cType minus 1 for the list cType itself
            + (in_array('list', $cType) ? count($listType) - 1 : 0);
    }

    public function getNewContentUrl(): string
    {
        if (!($this->definition['allowDirectNewLink'] ?? false)) {
            return parent::getNewContentUrl();
        }

        $pageId = $this->context->getPageId();

        $urlParameters = [
            'edit' => [
                'tt_content' => [
                    $pageId => 'new',
                ],
            ],
            'defVals' => [
                'tt_content' => [
                    'colPos' => $this->getColumnNumber(),
                    'sys_language_uid' => $this->container->getLanguage(),
                    'tx_container_parent' => $this->container->getUidOfLiveWorkspace(),
                    'uid_pid' => $this->newContentElementAtTopTarget,
                ],
            ],
            'returnUrl' => $GLOBALS['TYPO3_REQUEST']->getAttribute('normalizedParams')->getRequestUri(),
        ];
        $routeName = 'record_edit';

        $allowed = $this->definition['allowed'] ?? [];
        if (!empty($allowed)) {
            $cType = $allowed['CType'] ?? '';
            if ($cType) {
                $urlParameters['defVals']['tt_content']['CType'] = $cType;
            }

            $listType = $allowed['list_type'] ?? '';
            if ($listType) {
                $urlParameters['defVals']['tt_content']['list_type'] = $listType;
            }
        }

        /** @var UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return (string)$uriBuilder->buildUriFromRoute($routeName, $urlParameters);
    }

    public function getMinitems(): int
    {
        return $this->minitems;
    }
}
