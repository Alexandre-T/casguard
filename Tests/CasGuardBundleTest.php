<?php
/**
 * This file is part of the PhpCAS Guard Bundle.
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

use AlexandreT\Bundle\CasGuardBundle\CasGuardBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\ResolveClassPass;
use Symfony\Component\DependencyInjection\Compiler\ResolveInstanceofConditionalsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * CasGuardBundleTest class.
 *
 * @category AlexandreT\Bundle\CasGuardBundle\Tests
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class CasGuardBundleTest extends TestCase
{
    /**
     * Test the compiler passes.
     */
    public function testBuildCompilerPasses()
    {
        $container = new ContainerBuilder();
        $bundle = new CasGuardBundle();
        $bundle->build($container);
        $config = $container->getCompilerPassConfig();
        $passes = $config->getBeforeOptimizationPasses();

        $foundConditionalsPass = false;
        $foundResolveClassPass = false;

        if (version_compare(Kernel::VERSION, '3.2') < 1) {
            self::assertTrue(true); //no test for these version.
        } else {
            foreach ($passes as $pass) {
                if ($pass instanceof ResolveInstanceofConditionalsPass) {
                    $foundConditionalsPass = true;
                    continue;
                }

                if ($pass instanceof ResolveClassPass) {
                    $foundResolveClassPass = true;
                    continue;
                }
            }

            self::assertTrue($foundConditionalsPass, 'ResolveInstanceofConditionalsPass was not found');
            self::assertTrue($foundResolveClassPass, 'ResolveClassPass was not found');
        }
    }
}
