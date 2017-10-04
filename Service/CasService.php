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

namespace AlexandreT\Bundle\CasGuardBundle\Service;

use AlexandreT\Bundle\CasGuardBundle\Exception\CasException;

/**
 * CasService class.
 *
 * @category AlexandreT\Bundle\CasGuardBundle\Service
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class CasService implements CasServiceInterface
{
    private $configuration;

    /**
     * Cas service constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Return true if the internal PhpCAS debug activation tool is on.
     *
     * @return bool
     */
    public function getDebug()
    {
        return $this->getParameter('debug');
    }

    /**
     * Return the hostname of the server.
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->getParameter('hostname');
    }

    /**
     * Return the property.
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->getParameter('property');
    }

    /**
     * Return the Repository.
     *
     * @return string
     */
    public function getRepository()
    {
        return $this->getParameter('repository');
    }

    /**
     * Return the home page route.
     *
     * @return string
     */
    public function getRouteHomepage()
    {
        return $this->getRouteParameter('homepage');
    }

    /**
     * Return the login route.
     *
     * @return string
     */
    public function getRouteLogin()
    {
        return $this->getRouteParameter('login');
    }

    /**
     * Return the URI.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->getParameter('uri_login');
    }

    /**
     * Return the URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getParameter('url');
    }

    /**
     * Return the port number of the server.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->getParameter('port');
    }

    /**
     * Return the version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getParameter('version');
    }

    /**
     * Return a parameter or throw an exception if value is not declared.
     *
     * @param $key
     *
     * @throws CasException when $key is not a valid parameter.
     *
     * @return mixed
     */
    private function getParameter($key)
    {
        if (!key_exists($key, $this->configuration)) {
            throw new CasException(sprintf('The %s parameter must be defined. It is missing.', $key));
        }

        return $this->configuration[$key];
    }

    /**
     * Return a parameter or throw an exception if value is not declared.
     *
     * @param $key
     *
     * @throws CasException when $key is not a valid parameter.
     *
     * @return string
     */
    private function getRouteParameter($key)
    {
        if (!key_exists('route', $this->configuration)) {
            throw new CasException('The route parameter must be defined. It is missing.');
        }

        if (!key_exists($key, $this->configuration['route'])) {
            throw new CasException(sprintf('The %s parameter must be defined. It is missing.', $key));
        }

        return $this->configuration['route'][$key];
    }
}
