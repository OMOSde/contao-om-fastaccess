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

namespace OMOSde\ContaoOmFastaccessBundle\Controller\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '_contao_om_fastaccess_token', name: 'contao_om_fastaccess_token', defaults: ['_scope' => 'backend', '_token_check' => false])]
class ContaoOmFastaccessTokenController
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(['token' => md5(uniqid((string) mt_rand(), true))]);
    }
}
