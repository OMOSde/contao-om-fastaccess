<?php

/**
 * Contao module om_fastaccess
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @package   om_fastaccess
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */


/**
 * Namespace
 */
namespace OMOSde\ContaoOmFastaccessBundle\ContaoManager;


/**
 * Usages
 */
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use OMOSde\OmFastaccessBundleOMOSdeOmFastaccessBundle;


/**
 * Plugin for the Contao Manager.
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(OMOSdeContaoOmFastaccessBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
                ->setReplace(['om_fastaccess']),
        ];
    }
}
