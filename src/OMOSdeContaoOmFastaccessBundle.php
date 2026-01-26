<?php

/**
 * Contao bundle contao-om-fastaccess
 *
 * @copyright OMOS.de 2025 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */

namespace OMOSde\ContaoOmFastaccessBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OMOSdeContaoOmFastaccessBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
