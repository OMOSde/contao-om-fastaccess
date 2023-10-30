<?php

/**
 * Contao bundle contao-om-fastaccess
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */

use Contao\System;
use Symfony\Component\HttpFoundation\Request;

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'addFastAccess';
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] .= ';{fastAccess_legend},addFastAccess';


/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['addFastAccess'] = 'fastAccessToken,fastAccessJumpTo';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['addFastAccess'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['addFastAccess'],
    'inputType' => 'checkbox',
    'eval'      => array('submitOnChange'=>true),
    'sql'       => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_page']['fields']['fastAccessToken'] = array
(
    'label'         => &$GLOBALS['TL_LANG']['tl_page']['fastAccessToken'],
    'inputType'     => 'text',
    'load_callback' => array(array('tl_page_fastaccess', 'createToken')),
    'save_callback' => array(array('tl_page_fastaccess', 'saveToken')),
    'wizard'        => array(array('tl_page_fastaccess', 'createWizard')),
    'eval'          => array('maxlength'=>255, 'tl_class'=>'w50 wizard-fastaccess'),
    'sql'           => "varchar(100) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_page']['fields']['fastAccessJumpTo'] = array
(
    'label'         => &$GLOBALS['TL_LANG']['tl_page']['fastAccessJumpTo'],
    'exclude'       => true,
    'inputType'     => 'pageTree',
    'foreignKey'    => 'tl_page.title',
    'save_callback' => array(array('tl_page_fastaccess', 'saveCallbackJumpTo')),
    'eval'          => array('fieldType'=>'radio', 'tl_class'=>'clr'),
    'sql'           => "int(10) unsigned NOT NULL default '0'",
    'relation'      => array('type'=>'hasOne', 'load'=>'eager')
);


/**
 * Javascript
 */
if (
    System::getContainer()->get('contao.routing.scope_matcher')
    ->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))
) {
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/omosdecontaoomfastaccess/js/Ajax.js';
}


/**
 * Class tl_page_fastaccess
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_page_fastaccess extends Frontend
{

    /**
     * Create wizard buttons
     *
     * @return string
     */
    public function createWizard(DataContainer $dc)
    {
        $objPage = \PageModel::findWithDetails($dc->activeRecord->id);
        $objRootPage = \PageModel::findByPk($objPage->rootId);
        $strUrl = ($objRootPage->dns) ? 'http://' . $objRootPage->dns . '/' : Environment::get('base') . $objPage->getFrontendUrl() . '?token=' . $dc->value;

        $strRefresh = '<div class="button refresh" title="'.$GLOBALS['TL_LANG']['BTN']['wizardRefresh'].'"><svg onclick="Ajax.createToken(\''.\Environment::get('base').'\');" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg></div>';
        $strCopy    = '<div class="button copy" id="buttonCopy" data-clipboard-text="'.$strUrl.'" onclick="new Clipboard(\'.button.copy\');" title="'.$GLOBALS['TL_LANG']['BTN']['wizardCopy'].'"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></div>';

        return $strRefresh.$strCopy;
    }


    /**
     * Create a random token
     *
     * @param $varValue
     * @param DataContainer $dc
     *
     * @return string
     */
    public function createToken($varValue, DataContainer $dc)
    {
        if (!$varValue)
        {
            return md5(uniqid(mt_rand(), true));
        }

        return $varValue;
    }


    /**
     * Save a generated token
     *
     * @param $varValue
     * @param DataContainer $dc
     *
     * @return mixed
     */
    public function saveToken($varValue, DataContainer $dc)
    {
        return $varValue;
    }


    /**
     * Prevent redirect to protected or same page
     *
     * @param $varValue
     * @param DataContainer $dc
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function saveCallbackJumpTo($varValue, DataContainer $dc)
    {
        if (!$varValue)
        {
            return $varValue;
        }

        if ($varValue == $dc->activeRecord->id)
        {
            throw new Exception($GLOBALS['TL_LANG']['ERR']['redirectToSamePage']);
        }

        $objPage = \PageModel::findByPk($varValue);
        if ($objPage && $objPage->addFastAccess)
        {
            throw new Exception($GLOBALS['TL_LANG']['ERR']['selectedPageHasProtection']);
        }

        return $varValue;
    }
}
