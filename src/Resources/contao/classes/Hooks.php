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
 * Namespace
 */
namespace OMOSde\ContaoOmFastaccessBundle;


/**
 * Class Hooks
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class Hooks extends \Backend
{
    /**
     * HOOK
     *
     * @param PageModel $objPage
     * @param LayoutModel $objLayout
     * @param PageRegular $objPageRegular
     */
    public function handleToken(\PageModel $objPage, \LayoutModel  $objLayout, \PageRegular $objPageRegular)
    {
        if (
            $objPage->addFastAccess
            && '' !== $objPage->fastAccessToken
            && $objPage->fastAccessToken !== \Input::get('token')
        ) {
            // get page model from redirect page
            $page = \PageModel::findByPk($objPage->fastAccessJumpTo);

            // prevent redirect to same site
            if (null === $page || $page->id !== $objPage->id) {
                $this->redirect($page->getFrontendUrl());
            }
        }
    }
}
