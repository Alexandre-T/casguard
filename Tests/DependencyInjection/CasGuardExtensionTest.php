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

namespace AlexandreT\Bundle\CasGuardBundle\Tests\DependencyInjection;

use AlexandreT\Bundle\CasGuardBundle\DependencyInjection\CasGuardExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists('phpCAS')) {
            $this->markTestSkipped('PhpCas is not present');
        }

        $this->casGuardExtension = $this->getExtension();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->casGuardExtension = null;

        parent::tearDown();
    }

    /**
     * We test a valid configuration.
     */
    public function testValidConfiguration()
    {
        $this->casGuardExtension->load([
            'php_cas' => [
                    'debug' => true,
                    'hostname' => 'example.org',
                    'port' => 80,
                    'uri_login' => 'http://www.example.org/',
                    'url' => 'cas/login/',
                    'version' => CAS_VERSION_2_0,
                    'repository' => 'AppBundle:User',
                    'property' => 'mail',
                    'route' => [
                        'homepage' => 'home',
                        'login' => 'login',
                    ],
                ],
            ],
            $container = $this->getContainer()
        );

        $expected = [
            'debug' => true,
            'hostname' => 'example.org',
            'port' => 80,
            'uri_login' => 'http://www.example.org/',
            'url' => 'cas/login/',
            'version' => CAS_VERSION_2_0,
            'repository' => 'AppBundle:User',
            'property' => 'mail',
            'route' => [
                'homepage' => 'home',
                'login' => 'login',
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
