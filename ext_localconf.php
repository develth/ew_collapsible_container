<?php

declare(strict_types=1);

use B13\Container\Backend\Grid\ContainerGridColumn as BaseContainerGridColumn;
use B13\Container\Backend\Preview\ContainerPreviewRenderer as BaseContainerPreviewRenderer;
use B13\Container\Backend\Preview\GridRenderer as BaseGridRenderer;
use B13\Container\Tca\Registry as BaseRegistry;
use Evoweb\EwCollapsibleContainer\Xclass\ContainerGridColumn;
use Evoweb\EwCollapsibleContainer\Xclass\ContainerPreviewRenderer;
use Evoweb\EwCollapsibleContainer\Xclass\GridRenderer;
use Evoweb\EwCollapsibleContainer\Xclass\Registry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

if (ExtensionManagementUtility::isLoaded('container')) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][BaseContainerGridColumn::class] = [
        'className' => ContainerGridColumn::class,
    ];
    // @todo needed for $beforeContainerPreviewIsRendered begin
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][BaseContainerPreviewRenderer::class] = [
        'className' => ContainerPreviewRenderer::class,
    ];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][BaseGridRenderer::class] = [
        'className' => GridRenderer::class,
    ];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][BaseRegistry::class] = [
        'className' => Registry::class,
    ];
    // @todo needed for $beforeContainerPreviewIsRendered end
}
