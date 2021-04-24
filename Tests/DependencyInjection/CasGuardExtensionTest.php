<?php
/**
 * This file is part of the PhpCAS Guard Bundle.
 *
 * PHP version 7.3 | 7.4 | 8.0
 *
 * (c) Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * @category Tests
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license   MIT
 *
 * @see https://github.com/Alexandre-T/casguard/blob/master/LICENSE
 */

namespace AlexandreT\Bundle\CasGuardBundle\Tests\DependencyInjection;

use AlexandreT\Bundle\CasGuardBundle\DependencyInjection\CasGuardExtension;
use AlexandreT\Bundle\CasGuardBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * CasGuardExtensionTest class.
 *
 * @category Tests\DependencyInjection
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class CasGuardExtensionTest extends TestCase
{
    /**
     * CasGuard Extension to test.
     *
     * @var CasGuardExtension
     */
    private $casGuardExtension;

    /**
     * Configuration root name.
     *
     * @var string
     */
    private $root = 'cas_config';

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->casGuardExtension = $this->getExtension();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        $this->casGuardExtension = null;

        parent::tearDown();
    }

    /**
     * We test a valid configuration.
     *
     * @throws \Exception
     */
    public function testValidConfiguration()
    {
        $this->casGuardExtension->load([
            'php_cas' => [
                    'debug' => true,
                    'hostname' => 'example.org',
                    'port' => 80,
                    'url' => 'cas/login/',
                    'version' => Configuration::CAS_VERSION_2_0,
                    'route' => [
                        'homepage' => 'home',
                        'login' => 'login',
                    ],
                    'logout' => [
                        'supported' => true,
                        'handled' => true,
                        'allowed_clients' => ['foo', 'bar'],
                    ],
                ],
            ],
            $container = $this->getContainer()
        );

        $expected = [
            'certificate' => false,
            'debug' => true,
            'hostname' => 'example.org',
            'port' => 80,
            'url' => 'cas/login/',
            'verbose' => false,
            'version' => Configuration::CAS_VERSION_2_0,
            'route' => [
                'homepage' => 'home',
                'login' => 'login',
                'logout' => 'home',
            ],
            'language' => Configuration::PHPCAS_LANG_ENGLISH,
            'logout' => [
                'supported' => true,
                'handled' => true,
                'allowed_clients' => [
                    0 => 'foo',
                    1 => 'bar',
                ],
                'redirect_url' => false,
            ],
        ];

        $this->assertTrue($container->hasParameter($this->root));
        $this->assertEquals($expected, $container->getParameter($this->root));
    }

    /**
     * Return the extension.
     *
     * @return CasGuardExtension
     */
    protected function getExtension()
    {
        return new CasGuardExtension();
    }

    /**
     * Return the container builder.
     *
     * @return ContainerBuilder
     */
    private function getContainer()
    {
        $container = new ContainerBuilder();

        return $container;
    }
}
