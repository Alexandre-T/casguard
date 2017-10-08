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
        //Cas Service Creation
        $this->service = new CasService($this->loadConfiguration());

        self::assertEmpty($this->service->getDebug());
        self::assertFalse($this->service->getCertificate());
        self::assertInternalType('string', $this->service->getDebug());
        self::assertEquals('example.org', $this->service->getHostname());
        self::assertEquals(PHPCAS_LANG_ENGLISH, $this->service->getLanguage());
        self::assertEquals('username', $this->service->getProperty());
        self::assertEquals('App:User', $this->service->getRepository());
        self::assertEquals('security_login', $this->service->getRouteLogin());
        self::assertEquals('homepage', $this->service->getRouteHomepage());
        self::assertEquals(443, $this->service->getPort());
        self::assertEquals('foo', $this->service->getUri());
        self::assertEquals('cas/login', $this->service->getUrl());
        self::assertFalse($this->service->getVerbose());
        self::assertFalse($this->service->hasCertificate());
        self::assertEquals('3.0', $this->service->getVersion());
    }

    public function testHasCertificate()
    {
        $this->service = new CasService($this->loadConfiguration());
        self::assertFalse($this->service->hasCertificate());

        $this->service = new CasService($this->loadConfiguration(['certificate' => 'certificate.txt']));

        self::assertTrue($this->service->hasCertificate());
    }

    /**
     * Load configuration.
     *
     * @param array $actual
     *
     * @return mixed
     */
    private function loadConfiguration(array $actual = [])
    {
        $config = new Configuration();
        $actual = array_merge($actual, [
            'hostname' => 'example.org',
            'uri_login' => 'foo',
        ]);

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($actual);

        return $node->finalize($normalizedConfig);
    }
}
