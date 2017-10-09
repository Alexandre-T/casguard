<?php
/**
 * This file is part of the IP-Trevise Application.
 *
 * PHP version 7.1
 *
 * (c) Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * @category Entity
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @copyright 2017 Cerema
 * @license   CeCILL-B V1
 *
 * @see       http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 */

namespace AlexandreT\Bundle\CasGuardBundle\Tests\DependencyInjection;

use AlexandreT\Bundle\CasGuardBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends TestCase
{
    /**
     * Variable to test.
     *
     * @var Configuration
     */
    private $configuration;

    /**
     * Setup the configuration property before each test.
     */
    protected function setUp()
    {
        $this->configuration = new Configuration();
    }

    /**
     * Test that an empty configuration is throwing exception.
     */
    public function testUndefinedConfigTreeBuilder()
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('The child node "hostname" at path "phpcas_guard" must be configured.');
        $node = $this->configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([]);
        $node->finalize($normalizedConfig);
    }

    /**
     * Test that an empty hostname is throwing exception.
     */
    public function testEmptyConfigTreeBuilder()
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('The path "phpcas_guard.hostname" cannot contain an empty value, but got "".');
        $node = $this->configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'hostname' => '', //empty
        ]);
        $node->finalize($normalizedConfig);
    }

    /**
     * Test that an undefined login path is throwing an exception.
     */
    public function testUndefinedLoginConfigTreeBuilder()
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('The child node "uri_login" at path "phpcas_guard" must be configured.');
        $node = $this->configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'hostname' => 'foo.example.org',
        ]);
        $node->finalize($normalizedConfig);
    }

    /**
     * Test that an empty login path is throwing an exception.
     */
    public function testEmptyLoginConfigTreeBuilder()
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('The path "phpcas_guard.uri_login" cannot contain an empty value, but got "".');
        $node = $this->configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'hostname' => 'foo.example.org',
            'uri_login' => '', //empty
        ]);
        $node->finalize($normalizedConfig);
    }

    /**
     * Test CasGuardExtension->getContainer with invalid configuration.
     */
    public function testGetConfigWithWrongKey()
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('Unrecognized option "foo" under "phpcas_guard"');

        $node = $this->configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'hostname' => 'foo.example.org',
            'uri_login' => 'login',
            'foo' => 'bar',
        ]);
        $node->finalize($normalizedConfig);
    }

    /**
     * Test CasGuardExtension->getContainer with invalid configuration.
     */
    public function testGetConfigWithWrongValues()
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('The value "foo" is not allowed for path "phpcas_guard.version". Permissible values: "3.0", "2.0", "1.0"');

        $node = $this->configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize([
            'hostname' => 'foo.example.org',
            'uri_login' => 'login',
            'version' => 'foo',
        ]);
        $node->finalize($normalizedConfig);
    }

    /**
     * Test the default values.
     */
    public function testDefaultValuesConfigTreeBuilder()
    {
        $actual = [
            'hostname' => 'example.org',
            'uri_login' => 'foo',
            'logout' => [
                'allowed_clients' => [
                    0 => 'example1.org',
                    1 => 'example2.org',
                ],
            ],
        ];
        $expected = [
            'certificate' => false,
            'debug' => '',
            'hostname' => 'example.org',
            'port' => 443,
            'uri_login' => 'foo',
            'url' => 'cas/login',
            'verbose' => false,
            'version' => '3.0',
            'repository' => 'App:User',
            'property' => 'username',
            'route' => [
                'homepage' => 'homepage',
                'login' => 'security_login',
            ],
            'language' => Configuration::PHPCAS_LANG_ENGLISH,
            'logout' => [
                'supported' => true,
                'handled' => true,
                'allowed_clients' => [
                    'example1.org',
                    'example2.org',
                ],
            ],
        ];

        $node = $this->configuration->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($actual);
        $finalizedConfig = $node->finalize($normalizedConfig);

        $this->assertEquals($expected, $finalizedConfig);
    }
}
