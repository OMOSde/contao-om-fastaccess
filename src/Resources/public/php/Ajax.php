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
 * Return random token
 */
echo md5(uniqid(mt_rand(), true));
