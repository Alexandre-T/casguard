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
     * Return an array of host names allowed to send logout requests.
     *
     * @return array|null
     */
    public function getAllowedClients()
    {
        return $this->getLogoutParameter('allowed_clients');
    }

    /**
     * Return the certificate used to communicate with CAS server.
     *
     * @return string
     */
    public function getCertificate()
    {
        return $this->getParameter('certificate');
    }

    /**
     * Return true if the internal PhpCAS debug activation tool is on.
     *
     * @return string
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
     * Return the language for error message.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
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
        return "https://{$this->getHostname()}:{$this->getPort()}/{$this->getUrl()}";
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
     * Return the verbose mode.
     *
     * @return bool
     */
    public function getVerbose()
    {
        return $this->getParameter('verbose');
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
     * Return true if a certificate is used to communicate with the CAS server.
     */
    public function hasCertificate()
    {
        return false != $this->getParameter('certificate');
    }

    /**
     * Is the Cas Server Supporting the Single Sign Out Signal?
     *
     * @see https://wiki.jasig.org/display/CASUM/Single+Sign+Out
     *
     * @return bool
     */
    public function isSupportingSingleSignOutSignal()
    {
        return $this->getLogoutParameter('supported');
    }

    /**
     * The server is supporting single sign ou signal, but is this application using it?
     *
     * @return bool
     */
    public function isHandleLogoutRequest()
    {
        return $this->getLogoutParameter('handled');
    }

    /**
     * Return a parameter or throw an exception if value is not declared.
     *
     * @param $key
     *
     * @throws CasException when $key is not a valid parameter
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
     * @throws CasException when $key is not a valid parameter
     *
     * @return bool|array
     */
    private function getLogoutParameter($key)
    {
        if (!key_exists('logout', $this->configuration)) {
            throw new CasException('The logout parameter must be defined. It is missing.');
        }

        if (!key_exists($key, $this->configuration['logout'])) {
            throw new CasException(sprintf('The %s sub-parameter of logout parameter must be defined. It is missing.', $key));
        }

        return $this->configuration['logout'][$key];
    }

    /**
     * Return a parameter or throw an exception if value is not declared.
     *
     * @param $key
     *
     * @throws CasException when $key is not a valid parameter
     *
     * @return string
     */
    private function getRouteParameter($key)
    {
        if (!key_exists('route', $this->configuration)) {
            throw new CasException('The route parameter must be defined. It is missing.');
        }

        if (!key_exists($key, $this->configuration['route'])) {
            throw new CasException(sprintf('The %s sub-parameter of route parameter must be defined. It is missing.', $key));
        }

        return $this->configuration['route'][$key];
    }
}
