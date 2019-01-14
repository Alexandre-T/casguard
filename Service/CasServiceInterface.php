<?php
/**
 * This file is part of the PhpCAS Guard Bundle.
 *
 * PHP version 7.1 | 7.2
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
 * @category Service
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
interface CasServiceInterface
{
    /**
     * Return an array of host names allowed to send logout requests.
     *
     * @return array|null
     */
    public function getAllowedClients();

    /**
     * Return the certificate used to communicate with CAS server.
     *
     * @return string
     */
    public function getCertificate();

    /**
     * Return the filename used for phpCas logs or false when debug is disabled.
     *
     * If empty, phpcas will use the default log file ("os_tmp_dir"/phpcas.log)
     *
     * @return bool|string
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
     * The route where user is redirected after logout.
     *
     * It could be the home route of your application.
     *
     * @return string
     */
    public function getRouteLogout();

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
     * Return the Cas protocol version used.
     *
     * @return string
     */
    public function getVersion();

    /**
     * Return true if a certificate is used to communicate with CAS server.
     *
     * @return bool
     */
    public function hasCertificate();

    /**
     * The server is supporting single sign ou signal, but is this application using it?
     *
     * @return bool
     */
    public function isHandleLogoutRequest();

    /**
     * Is the Cas Server Supporting the Single Sign Out Signal?
     *
     * @see https://wiki.jasig.org/display/CASUM/Single+Sign+Out
     *
     * @return bool
     */
    public function isSupportingSingleSignOutSignal();

    /**
     * Is user redirect after logout.
     *
     * It could be a link on the logout page on your CAS Server.
     *
     * @return bool
     */
    public function isRedirectingAfterLogout();

    /**
     * Return the verbose mode.
     *
     * @return bool
     */
    public function isVerbose();
}
