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

namespace AlexandreT\Bundle\CasGuardBundle\Security;

use AlexandreT\Bundle\CasGuardBundle\Service\CasServiceInterface;
use phpCAS;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

/**
 * Cas Authenticator.
 *
 * @category Security
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class CasAuthenticator extends AbstractGuardAuthenticator implements LogoutSuccessHandlerInterface
{
    /**
     * Router Interface.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * CasService interface.
     *
     * @var CasServiceInterface
     */
    private $cas;

    /**
     * Cas Authenticator constructor.
     *
     * @param RouterInterface     $router
     * @param CasServiceInterface $cas
     */
    public function __construct(RouterInterface $router, CasServiceInterface $cas)
    {
        $this->router = $router;
        $this->cas = $cas;
    }

    /**
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     *
     * @param Request $request
     *
     * @return null|string
     */
    public function getCredentials(Request $request)
    {
        phpCAS::setDebug($this->cas->getDebug());
        phpCAS::setVerbose($this->cas->isVerbose());
        phpCAS::client(
            $this->cas->getVersion(),
            $this->cas->getHostname(),
            $this->cas->getPort(),
            $this->cas->getUrl()
        );
        phpCAS::setLang($this->cas->getLanguage());

        if ($this->cas->hasCertificate()) {
            phpCAS::setCasServerCACert($this->cas->getCertificate());
        } else {
            phpCAS::setNoCasServerValidation();
        }

        /* @see https://wiki.jasig.org/display/CASC/phpCAS+examples#phpCASexamples-HandlelogoutrequestsfromtheCASserver */
        if ($this->cas->isSupportingSingleSignOutSignal()) {
            if (!is_null($this->cas->getAllowedClients()) && count($this->cas->getAllowedClients())) {
                phpCAS::handleLogoutRequests($this->cas->isHandleLogoutRequest(), $this->cas->getAllowedClients());
            } else {
                phpCAS::handleLogoutRequests($this->cas->isHandleLogoutRequest());
            }
        }

        phpCAS::forceAuthentication();

        // Return User
        if (phpCAS::getUser()) {
            return phpCAS::getUser();
        }

        return null;
    }

    /**
     * Return the user from application.
     *
     * @param string                $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return null|object
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials);
    }

    /**
     * Check credentials.
     *
     * Credentials are always returning true, because authentication is done by CAS.
     *
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool true
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey The provider (i.e. firewall) key
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        );

        return new JsonResponse($data, 403);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     *
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        //The URL have to be completed by the current request uri,
        // because Cas Server need to know where redirect user after authentication.
        return new RedirectResponse($this->cas->getUri().$request->getUri());
    }

    /**
     * Generate the default Success redirect url.
     *
     * @return string
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate($this->cas->getRouteHomepage());
    }

    /**
     * Generate the Login URL in router.
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate($this->cas->getRouteLogin());
    }

    /**
     * Does this method support remember me cookies?
     *
     * No, we do not support remember me cookie, because CAS server is doing it.
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * Logout and redirect to home page.
     *
     * @param Request $request
     *
     * @return void
     */
    public function onLogoutSuccess(Request $request)
    {
        phpCAS::setDebug($this->cas->getDebug());
        phpCAS::setVerbose($this->cas->isVerbose());
        phpCAS::client(
            $this->cas->getVersion(),
            $this->cas->getHostname(),
            $this->cas->getPort(),
            $this->cas->getUrl()
        );
        phpCAS::setLang($this->cas->getLanguage());

        if ($this->cas->isRedirectingAfterLogout()) {
            $uri = $this->router->generate(
                $this->cas->getRouteLogout(),
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            phpCAS::logoutWithRedirectService($uri);
        } else {
            //simple logout
            phpCAS::logout();
        }
    }

    /**
     * All pages are managed by this Authenticator.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return true;
    }
}
