<?php
/**
 * This file is part of the  PhpCAS Guard Bundle.
 *
 * PHP version 5.6 | 7.0 | 7.1
 *
 * (c) Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * @category Entity
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license   MIT
 *
 * @see https://github.com/Alexandre-T/casguard/blob/master/LICENSE
 */

namespace AlexandreT\Bundle\CasGuardBundle\Tests;

use AlexandreT\Bundle\CasGuardBundle\DependencyInjection\CasGuardExtension;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        if (!class_exists('Doctrine\\Common\\Version')) {
            $this->markTestSkipped('Doctrine is not available.');
        }
    }

    public function createYamlBundleTestContainer()
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.name' => 'app',
            'kernel.debug' => false,
            'kernel.bundles' => array('YamlBundle' => 'Fixtures\Bundles\YamlBundle\YamlBundle'),
            'kernel.cache_dir' => sys_get_temp_dir(),
            'kernel.environment' => 'test',
            'kernel.root_dir' => __DIR__.'/../../../../', // src dir
        )));
        $extension = new CasGuardExtension();
        $container->registerExtension($extension);
        $extension->load([
        ], $container);

        $container->compile();

        return $container;
    }
}
