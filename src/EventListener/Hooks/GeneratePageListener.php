<?php

/**
 * Contao bundle contao-om-fastaccess
 *
 * @copyright OMOS.de 2025 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      https://github.com/OMOSde/contao-om-fastaccess
 * @license   LGPL 3.0+
 */

declare(strict_types=1);

namespace OMOSde\ContaoOmFastaccessBundle\EventListener\Hooks;

use Contao\Controller;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\Input;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Contao\System;

#[AsHook('generatePage')]
class GeneratePageListener
{
    public function __invoke(PageModel $pageModel, LayoutModel $layout, PageRegular $pageRegular): void
    {
        $router = System::getContainer()->get('contao.routing.content_url_generator');

        if (
            $pageModel->addFastAccess
            && '' !== $pageModel->fastAccessToken
            && $pageModel->fastAccessToken !== Input::get('token')
        ) {
            // Send 403
            if (!$pageModel->fastAccessJumpTo) {
                throw new AccessDeniedException($GLOBALS['TL_LANG']['ERR']['redirectPageMissing']);
            }

            // Get page model from redirect page
            $page = PageModel::findByPk($pageModel->fastAccessJumpTo);

            // Prevent redirect to the same site
            if (null === $page || $page->id !== $pageModel->id) {
                Controller::redirect($router->generate($page));
            }
        }
    }
}
