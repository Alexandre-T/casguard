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
use AlexandreT\Bundle\CasGuardBundle\Exception\CasException;
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
        self::assertEquals('security_login', $this->service->getRouteLogin());
        self::assertEquals('homepage', $this->service->getRouteHomepage());
        self::assertEquals(443, $this->service->getPort());
        self::assertEquals('https://example.org:443/cas/login', $this->service->getUri());
        self::assertEquals('cas/login', $this->service->getUrl());
        self::assertFalse($this->service->isVerbose());
        self::assertFalse($this->service->hasCertificate());
        self::assertEquals('3.0', $this->service->getVersion());
    }

    /**
     * Test hasCertificate() method.
     */
    public function testHasCertificate()
    {
        $this->service = new CasService($this->loadConfiguration());
        self::assertFalse($this->service->hasCertificate());

        $this->service = new CasService($this->loadConfiguration(['certificate' => false]));
        self::assertFalse($this->service->hasCertificate());

        $this->service = new CasService($this->loadConfiguration(['certificate' => null]));
        self::assertFalse($this->service->hasCertificate());

        $this->service = new CasService($this->loadConfiguration(['certificate' => true]));
        self::assertTrue($this->service->hasCertificate());

        $this->service = new CasService($this->loadConfiguration(['certificate' => 'certificate.txt']));
        self::assertTrue($this->service->hasCertificate());
    }

    /**
     * test private getParameter() method with reflection class.
     */
    public function testPrivateGetParameter()
    {
        $casService = new CasService(['foo' => 'bar']);
        $class = new \ReflectionClass($casService);
        $method = $class->getMethod('getParameter');
        $method->setAccessible(true);
        $output = $method->invoke($casService, 'foo');

        self::assertEquals('bar', $output);
    }

    /**
     * test private getParameter() method with reflection class and a non-existent parameter.
     */
    public function testPrivateGetNonExistentParameter()
    {
        self::expectException(CasException::class);
        self::expectExceptionMessage('The non-existent parameter must be defined. It is missing.');

        $casService = new CasService(['foo' => 'bar']);
        $class = new \ReflectionClass($casService);
        $method = $class->getMethod('getParameter');
        $method->setAccessible(true);
        $method->invoke($casService, 'non-existent');
    }

    /**
     * test private getRouteParameter() method with reflection class.
     */
    public function testPrivateGetRouteParameter()
    {
        $casService = new CasService([
            'route' => [
                'foo' => 'bar',
            ],
        ]);
        $class = new \ReflectionClass($casService);
        $method = $class->getMethod('getRouteParameter');
        $method->setAccessible(true);
        $output = $method->invoke($casService, 'foo');

        self::assertEquals('bar', $output);
    }

    /**
     * test private getParameter() method with reflection class and a non-existent parameter.
     */
    public function testPrivateGetNonExistentRouteParameter()
    {
        self::expectException(CasException::class);
        self::expectExceptionMessage('The route parameter must be defined. It is missing.');

        $casService = new CasService([
            'foo2' => [
                'foo' => 'bar',
            ],
        ]);
        $class = new \ReflectionClass($casService);
        $method = $class->getMethod('getRouteParameter');
        $method->setAccessible(true);
        $method->invoke($casService, 'non-existent');
    }

    /**
     * test private getParameter() method with reflection class and a non-existent parameter.
     */
    public function testPrivateGetNonExistentRouteSubParameter()
    {
        self::expectException(CasException::class);
        self::expectExceptionMessage('The non-existent sub-parameter of route parameter must be defined. It is missing.');

        $casService = new CasService([
            'route' => [
                'foo' => 'bar',
            ],
        ]);
        $class = new \ReflectionClass($casService);
        $method = $class->getMethod('getRouteParameter');
        $method->setAccessible(true);
        $method->invoke($casService, 'non-existent');
    }

    /**
     * test private getParameter() method with reflection class and a non-existent parameter.
     */
    public function testPrivateGetNonExistentLogoutParameter()
    {
        self::expectException(CasException::class);
        self::expectExceptionMessage('The logout parameter must be defined. It is missing.');

        $casService = new CasService([
            'foo2' => [
                'foo' => 'bar',
            ],
        ]);
        $class = new \ReflectionClass($casService);
        $method = $class->getMethod('getLogoutParameter');
        $method->setAccessible(true);
        $method->invoke($casService, 'non-existent');
    }

    /**
     * test private getParameter() method with reflection class and a non-existent parameter.
     */
    public function testPrivateGetNonExistentLogoutSubParameter()
    {
        self::expectException(CasException::class);
        self::expectExceptionMessage('The non-existent sub-parameter of logout parameter must be defined. It is missing.');

        $casService = new CasService([
            'logout' => [
                'foo' => 'bar',
            ],
        ]);
        $class = new \ReflectionClass($casService);
        $method = $class->getMethod('getLogoutParameter');
        $method->setAccessible(true);
        $method->invoke($casService, 'non-existent');
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
        ]);

        $node = $config->getConfigTreeBuilder()->buildTree();
        $normalizedConfig = $node->normalize($actual);

        return $node->finalize($normalizedConfig);
    }
}
