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

use Doctrine\ORM\EntityManager;
use phpCAS;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
     * Entity Manager Interface.
     *
     * @var EntityManager
     */
    private $em;

    /**
     * Token storage interface.
     *
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * Router Interface.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Array of configuration
     *
     * @var array
     */
    private $config;

    /**
     * Cas Authenticator constructor.
     *
     * @param EntityManager         $em
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface       $router
     * @param array                 $config
     */
    public function __construct(EntityManager $em, TokenStorageInterface $tokenStorage, RouterInterface $router, array $config)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->config = $config;
        //dump($config);
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
        //phpCAS::setDebug();
        phpCAS::setVerbose(true);
        //phpCAS::setLang(PHPCAS_LANG_FRENCH);
        phpCAS::client(
            $this->config['version'],
            $this->config['hostname'],
            $this->config['port'],
            $this->config['uri']);
        //phpCAS::setCasServerCACert($mon_certificat);
        phpCAS::setNoCasServerValidation();
        //phpCAS::handleLogoutRequests();
        phpCAS::forceAuthentication();

        if (phpCAS::getUser()) {
            return phpCAS::getUser();
        }

        return null;
    }

    /**
     * getUser function.
     *
     * @param string $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return null|object
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $repository = $this->em->getRepository($this->config['repository']);

        return $repository->findOneBy([
            $this->config['column'] => $credentials
        ]);
    }

    /**
     *
     *
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * What is done when user authentification is valid.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //TODO add a flashbag message
        return null;
    }

    /**
     * What to do when the authentification failed.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        //TODO Add a flashbag message.
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );
        return new JsonResponse($data, 403);
    }

    /**
     * Called when authentication is needed, but it's not sent
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->config['uri_login'] . $request->getUri());
    }

    /**
     * Generate the default Success redirect url
     *
     * @return string
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('homepage');
    }

    /**
     * Generate the Login URL in router.
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    /**
     * We do not support "Remember me" function.
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
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        phpCAS::client(
            $this->config['version'],
            $this->config['hostname'],
            $this->config['port'],
            $this->config['uri']
        );
        phpCAS::logout();
        return new RedirectResponse($this->router->generate('homepage'));
    }
}
