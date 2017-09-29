<?php
/**
 * This file is part of the PhpCAS Guard Bundle.
 *
 * PHP version 5.6 | 7.0
 *
 * (c) Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * @category Entity
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @copyright 2017 Cerema
 * @license   CeCILL-B V1
 */
namespace AlexandreT\Bundle\CasGuardBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Cas Guard Bundle.
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class CasGuardBundle extends Bundle {
    public function build(ContainerBuilder $container) {
        parent::build($container);
        //$extension = $container->getExtension('security');
        //$extension->addSecurityListenerFactory(new CasFactory());
    }
}
