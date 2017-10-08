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

namespace AlexandreT\Bundle\CasGuardBundle\Tests\Service;

use AlexandreT\Bundle\CasGuardBundle\DependencyInjection\Configuration;
use AlexandreT\Bundle\CasGuardBundle\Service\CasService;
use PHPUnit\Framework\TestCase;

/**
 * CasServiceTest class.
 *
 * @category AlexandreT\Bundle\CasGuardBundle\Tests\Service
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class CasServiceTest extends TestCase
{
    /**
     * Service Cas to test.
     *
     * @var CasService
     */
    private $service;

    /**
     * Test the service initialization with some Data.
     */
    public function testService()
    {
        $config = new Configuration();
        $actual = [
            'hostname' => 'example.org',
            'uri_login' => 'foo',
        ];

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($actual);
        $finalizedConfig = $node->finalize($normalizedConfig);

        //Cas Service Creation
        $this->service = new CasService($finalizedConfig);

        self::assertEmpty($this->service->getDebug());
        self::assertInternalType('string', $this->service->getDebug());
        self::assertEquals('example.org', $this->service->getHostname());
        self::assertEquals(443, $this->service->getPort());
        self::assertEquals('foo', $this->service->getUri());
        self::assertEquals('3.0', $this->service->getVersion());
        self::assertEquals('App:User', $this->service->getRepository());
        self::assertEquals('username', $this->service->getProperty());
        self::assertEquals('cas/login', $this->service->getUrl());
        self::assertEquals('security_login', $this->service->getRouteLogin());
        self::assertEquals('homepage', $this->service->getRouteHomepage());
    }
}
