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

use Contao\System;
use Symfony\Component\HttpFoundation\Request;

// Palettes
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'addFastaccess';
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] .= ';{fastaccess_legend},addFastaccess';

// Subpalettes
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['addFastaccess'] = 'fastaccessToken,fastaccessJumpTo';

// Fields
$GLOBALS['TL_DCA']['tl_page']['fields']['addFastaccess'] = [
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_page']['fields']['fastaccessToken'] = [
    'inputType' => 'text',
    'eval' => ['maxlength' => 255, 'tl_class' => 'w50 wizard-fastaccess'],
    'sql' => "varchar(100) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_page']['fields']['fastaccessJumpTo'] = [
    'exclude' => true,
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio', 'tl_class' => 'clr'],
    'sql' => "int(10) unsigned NOT NULL default '0'",
    'relation' => ['type' => 'hasOne', 'load' => 'eager']
];

// JavaScript
if (System::getContainer()->get('contao.routing.scope_matcher')
    ->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))
) {
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/omosdecontaoomfastaccess/js/contao-om-fastaccess.js';
}
