<?php

/**
 * Contao bundle contao-om-fastaccess
 *
 * @copyright OMOS.de 2025 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */

declare(strict_types=1);

namespace OMOSde\ContaoOmFastaccessBundle\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\Environment;
use Contao\PageModel;
use Contao\System;
use Exception;

class PageDataContainer
{
    #[AsCallback(table: 'tl_page', target: 'config.onload')]
    public function configOnload(): void
    {
        $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoomfastaccess/css/contao-om-fastaccess.css|static';
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/omosdecontaoomfastaccess/js/clipboard/clipboard.min.js|static';
    }

    #[AsCallback(table: 'tl_page', target: 'fields.fastAccessToken.load')]
    public function fieldsFastaccessTokenLoad($value): mixed
    {
        if (!$value) {
            return md5(uniqid((string)mt_rand(), true));
        }

        return $value;
    }

    #[AsCallback(table: 'tl_page', target: 'fields.fastAccessToken.save')]
    public function fieldsFastaccessTokenSave($value): mixed
    {
        return $value;
    }

    #[AsCallback(table: 'tl_page', target: 'fields.fastAccessToken.wizard')]
    public function fieldsFastaccessTokenWizard(DataContainer $dataContainer): string
    {
        $router = System::getContainer()->get('contao.routing.content_url_generator');

        $pageModel = PageModel::findWithDetails($dataContainer->activeRecord->id);
        $rootPageModel = PageModel::findByPk($pageModel->rootId);

        $url = ($rootPageModel->dns) ? 'https://' . $rootPageModel->dns : Environment::get('base') . $router->generate($pageModel) . '?token=' . $dataContainer->value;

        $htmlRefresh = '<div class="button refresh" title="' . $GLOBALS['TL_LANG']['BTN']['wizardRefresh'] . '"><svg onclick="ContaoOmFastaccess.createToken(\'' . Environment::get('base') . '\');" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg></div>';
        $htmlCopy = '<div class="button copy" id="buttonCopy" data-clipboard-text="' . $url . '" onclick="new ClipboardJS(\'.button.copy\');" title="' . $GLOBALS['TL_LANG']['BTN']['wizardCopy'] . '"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></div>';

        return $htmlRefresh . $htmlCopy;
    }

    #[AsCallback(table: 'tl_page', target: 'fields.fastAccessJumpTo.save')]
    public function fieldsFastaccessJumpToSave($value, DataContainer $dataContainer): mixed
    {
        if (!$value) {
            return $value;
        }

        if ($value == $dataContainer->activeRecord->id) {
            throw new Exception($GLOBALS['TL_LANG']['ERR']['redirectToSamePage']);
        }

        $pageModel = PageModel::findByPk($value);
        if ($pageModel && $pageModel->addFastAccess) {
            throw new Exception($GLOBALS['TL_LANG']['ERR']['selectedPageHasProtection']);
        }

        return $value;
    }
}
