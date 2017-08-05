<?php

/**
 * Contao bundle contao-om-fastaccess
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */


/**
 * CSS and Javascripts
 */
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoomfastaccess/css/contao-om-fastaccess.css|static';

    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/omosdecontaoomfastaccess/js/clipboard/clipboard.min.js|static';
}


/**
 * Register hook to check fastaccess
 */
$GLOBALS['TL_HOOKS']['generatePage'][] = array('OMOSde\ContaoOmFastaccessBundle\Hooks', 'handleToken');
