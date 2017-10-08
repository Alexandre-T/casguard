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

/**
 * Interface CasServiceInterface.
 *
 * @category AlexandreT\Bundle\CasGuardBundle\Service
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
interface CasServiceInterface
{
    /**
     * Return true if debugging mode is enabled.
     *
     * Default value must be false.
     *
     * @return bool
     */
    public function getDebug();

    /**
     * Return the hostname of the CAS server used.
     *
     * @return string
     */
    public function getHostname();

    /**
     * Return the language for error message.
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Return the name of the property to retrieve User in its repository.
     *
     * Default value is username, mail could be a good value too.
     *
     * @return mixed
     */
    public function getProperty();

    /**
     * Repository name of entity manager to retrieve user.
     *
     * @return string
     */
    public function getRepository();

    /**
     * Your homepage route.
     *
     * @return string
     */
    public function getRouteHomepage();

    /**
     * Your security login route.
     *
     * @return string
     */
    public function getRouteLogin();

    /**
     * Return the port used by your CAS service on your server.
     *
     * @return int
     */
    public function getPort();

    /**
     * Return the complete URI to login on your CAS server.
     *
     * @return string
     */
    public function getUri();

    /**
     * Return the URL of the service.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Return the verbose mode.
     *
     * @return boolean
     */
    public function getVerbose();

    /**
     * Return the Cas protocol version used.
     *
     * @return string
     */
    public function getVersion();
}
