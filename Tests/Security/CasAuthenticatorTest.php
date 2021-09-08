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

namespace AlexandreT\Bundle\CasGuardBundle\Tests\Security;

use AlexandreT\Bundle\CasGuardBundle\DependencyInjection\Configuration;
use AlexandreT\Bundle\CasGuardBundle\Security\CasAuthenticator;
use AlexandreT\Bundle\CasGuardBundle\Service\CasService;
use AspectMock\Proxy\Verifier;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use AspectMock\Test as test;
use phpCas;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * CasAuthenticatorTest class.
 *
 * @category Tests\Security
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class CasAuthenticatorTest extends TestCase
{
    /**
     * Configuration.
     *
     * @var array
     */
    private $configuration = [];

    /**
     * The guard authenticator to test.
     *
     * @var CasAuthenticator
     */
    private $guardAuthenticator;

    /**
     * The router is mocked.
     *
     * @var RouterInterface|MockObject
     */
    private $router;

    /**
     * The cas service used by the authenticator.
     *
     * @var CasService
     */
    private $casService;

    /**
     * test : Check credentials method which is always returning true.
     */
    public function testCheckCredentials()
    {
        /** @var UserInterface $user Mocked user interface */
        $user = $this->getMockBuilder(UserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::assertTrue($this->guardAuthenticator->checkCredentials(null, $user));
    }

    /**
     * This is a test to validate AspectMock configuration.
     *
     * @see https://stackoverflow.com/questions/13734224/exception-serialization-of-closure-is-not-allowed
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testAspectMock()
    {
        $phpCas = test::double('phpCAS', ['setDebug' => function () {
            echo 'YES I CALL THE MOCKED Debug function';
        }]);

        phpCAS::setLogger();
        $phpCas->verifyInvoked('setDebug', false);
        self::expectOutputString('YES I CALL THE MOCKED Debug function');
    }

    /**
     * Test the first example in phpcas documentation with a connected user (foo).
     *
     * phpCAS can be used the simplest way, as a CAS client.
     *
     * @see https://github.com/apereo/phpCAS/blob/master/docs/examples/example_simple.php
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testExampleSimple()
    {
        $expected = $actual = 'foo';

        $this->configuration['certificate'] = 'certificate.txt'; //we use a certificate
        $this->configuration['logout']['supported'] = true; //we use single sign out signal
        $this->configuration['logout']['handled'] = true; //we use single sign out signal
        $this->configuration['logout']['allowed_clients'] = ['foo', 'bar']; //we use single sign out signal

        $this->reloadConfiguration();

        $phpCas = $this->mockPhpCAS([
            'getUser' => $actual, //we return a user
        ]);

        //The first request call credentials and return a user (here this is a string)
        self::assertEquals($expected, $this->guardAuthenticator->getCredentials(new Request()));

        $phpCas->verifyInvokedOnce('setDebug');
        $phpCas->verifyInvokedOnce('client');
        $phpCas->verifyInvokedOnce('setLang');
        $phpCas->verifyInvokedOnce('setVerbose');
        $phpCas->verifyInvokedOnce('setCasServerCACert');
        $phpCas->verifyInvokedOnce('forceAuthentication');
        $phpCas->verifyInvokedOnce('handleLogoutRequests');
        $phpCas->verifyNeverInvoked('setNoCasServerValidation');
        $phpCas->verifyInvokedMultipleTimes('getUser', 2);
    }

    /**
     * Test the first example in phpcas documentation with a connected user (foo).
     *
     * phpCAS can be used the simplest way, as a CAS client.
     *
     * @see https://github.com/apereo/phpCAS/blob/master/docs/examples/example_simple.php
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testExampleSimpleWithoutAllowedClients()
    {
        $expected = $actual = 'foo';

        $this->configuration['certificate'] = 'certificate.txt'; //we use a certificate
        $this->configuration['logout']['supported'] = true; //we use single sign out signal
        $this->configuration['logout']['handled'] = true; //we use single sign out signal
        $this->configuration['logout']['allowed_clients'] = null; //we use single sign out signal

        $this->reloadConfiguration();

        $phpCas = $this->mockPhpCAS([
            'getUser' => $actual, //we return a user
        ]);

        //The first request call credentials and return a user (here this is a string)
        self::assertEquals($expected, $this->guardAuthenticator->getCredentials(new Request()));

        $phpCas->verifyInvokedOnce('setDebug');
        $phpCas->verifyInvokedOnce('client');
        $phpCas->verifyInvokedOnce('setLang');
        $phpCas->verifyInvokedOnce('setVerbose');
        $phpCas->verifyInvokedOnce('setCasServerCACert');
        $phpCas->verifyInvokedOnce('forceAuthentication');
        $phpCas->verifyInvokedOnce('handleLogoutRequests');
        $phpCas->verifyNeverInvoked('setNoCasServerValidation');
        $phpCas->verifyInvokedMultipleTimes('getUser', 2);
    }

    /**
     * Test the first example in phpcas documentation with a connected user (foo).
     *
     * phpCAS can be used the simplest way, as a CAS client.
     *
     * @see https://github.com/apereo/phpCAS/blob/master/docs/examples/example_simple.php
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testExampleSimpleWithEmptyAllowedClients()
    {
        $expected = $actual = 'foo';

        $this->configuration['certificate'] = 'certificate.txt'; //we use a certificate
        $this->configuration['logout']['supported'] = true; //we use single sign out signal
        $this->configuration['logout']['handled'] = true; //we use single sign out signal
        $this->configuration['logout']['allowed_clients'] = []; //we use single sign out signal

        $this->reloadConfiguration();

        $phpCas = $this->mockPhpCAS([
            'getUser' => $actual, //we return a user
        ]);

        //The first request call credentials and return a user (here this is a string)
        self::assertEquals($expected, $this->guardAuthenticator->getCredentials(new Request()));

        $phpCas->verifyInvokedOnce('setDebug');
        $phpCas->verifyInvokedOnce('client');
        $phpCas->verifyInvokedOnce('setLang');
        $phpCas->verifyInvokedOnce('setVerbose');
        $phpCas->verifyInvokedOnce('setCasServerCACert');
        $phpCas->verifyInvokedOnce('forceAuthentication');
        $phpCas->verifyInvokedOnce('handleLogoutRequests');
        $phpCas->verifyNeverInvoked('setNoCasServerValidation');
        $phpCas->verifyInvokedMultipleTimes('getUser', 2);
    }

    /**
     * Test the first example in phpcas documentation with a connected user (foo).
     *
     * In this test the server supports SSOS but it is not used.
     *
     * @see https://github.com/apereo/phpCAS/blob/master/docs/examples/example_simple.php
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testExampleSingleSignOutRefused()
    {
        $expected = $actual = 'foo';

        $this->configuration['logout']['supported'] = true; //we use single sign out signal
        $this->configuration['logout']['handled'] = false; //we use single sign out signal
        $this->configuration['logout']['allowed_clients'] = null; //we use single sign out signal

        $this->reloadConfiguration();

        $phpCas = $this->mockPhpCAS([
            'getUser' => $actual, //we return a user
        ]);

        //The first request call credentials and return a user (here this is a string)
        self::assertEquals($expected, $this->guardAuthenticator->getCredentials(new Request()));

        $phpCas->verifyInvokedOnce('setDebug');
        $phpCas->verifyInvokedOnce('client');
        $phpCas->verifyInvokedOnce('setLang');
        $phpCas->verifyInvokedOnce('setVerbose');
        $phpCas->verifyInvokedOnce('forceAuthentication');
        $phpCas->verifyInvokedOnce('handleLogoutRequests');
        $phpCas->verifyInvokedOnce('setNoCasServerValidation');
        $phpCas->verifyNeverInvoked('setCasServerCACert');
        $phpCas->verifyInvokedMultipleTimes('getUser', 2);
    }

    /**
     * Test the first example in phpcas documentation with no connected user.
     *
     * phpCAS can be used the simplest way, as a CAS client.
     *
     * @see https://github.com/apereo/phpCAS/blob/master/docs/examples/example_simple.php
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testExampleSimpleWithoutUser()
    {
        $phpCas = $this->mockPhpCAS();

        //The second request call credentials and do not return user
        self::assertNull($this->guardAuthenticator->getCredentials(new Request()));

        $phpCas->verifyInvokedOnce('setDebug');
        $phpCas->verifyInvokedOnce('client');
        $phpCas->verifyInvokedOnce('setVerbose');
        $phpCas->verifyInvokedOnce('setLang');
        $phpCas->verifyInvokedOnce('setNoCasServerValidation');
        $phpCas->verifyNeverInvoked('setCasServerCACert');
        $phpCas->verifyInvokedOnce('forceAuthentication');
        $phpCas->verifyNeverInvoked('handleLogoutRequests');
        $phpCas->verifyInvokedMultipleTimes('getUser', 1);
    }

    /**
     * Test getuser method().
     */
    public function testGetUser()
    {
        $expected = 'toto';

        /** @var UserProviderInterface|MockObject $user */
        $user = $this->getMockBuilder(UserProviderInterface::class)
            ->getMock();

        $user
            ->expects(self::once())
            ->method('loadUserByUsername')
            ->with('foo')
            ->willReturn('toto');

        self::assertEquals($expected, $this->guardAuthenticator->getUser('foo', $user));
    }

    /**
     * Test onAuthenticationSuccess() method without initialization to avoid exception (client or proxy not called).
     *
     * @throws \Exception
     */
    public function testOnAuthenticationSuccessWithoutInitialization()
    {
        $phpCas = $this->mockPhpCAS();

        /** @var TokenInterface $token Mocked token */
        $token = $this->getMockBuilder(TokenInterface::class)
            ->getMock();

        self::assertNull($this->guardAuthenticator->onAuthenticationSuccess(new Request(), $token, 'key'));

        $phpCas->verifyInvokedOnce('isInitialized');
        $phpCas->verifyNeverInvoked('getAttributes');

    }

    /**
     * Test onAuthenticationSuccess() method with initialization.
     *
     * @throws \Exception
     */
    public function testOnAuthenticationSuccessWithInitialization()
    {
        $expected = $actual = ['foo' => 'bar'];
        $phpCas = $this->mockPhpCAS();
        test::double('phpCAS', ['isInitialized' => true]);
        test::double('phpCAS', ['getAttributes' => $expected]);

        /** @var MockObject|TokenInterface $token Mocked token */
        $token = $this->getMockBuilder(TokenInterface::class)
            ->getMock();

        $token->expects(self::once())
            ->method('setAttributes')
            ->with($expected);

        self::assertNull($this->guardAuthenticator->onAuthenticationSuccess(new Request(), $token, 'key'));

        $phpCas->verifyInvokedOnce('isInitialized');
        $phpCas->verifyInvokedOnce('getAttributes');



    }

    /**
     * Test onAuthenticationFailure() method.
     */
    public function testOnAuthenticationFailure()
    {
        $authenticationException = new AuthenticationException('foo message');

        $response = $this->guardAuthenticator->onAuthenticationFailure(new Request(), $authenticationException);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(403, $response->getStatusCode());
        self::assertEquals('{"message":"An authentication exception occurred."}', $response->getContent());
    }

    /**
     * Test onLogoutSuccess() method without redirection.
     *
     * @throws \Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
     */
    public function testOnLogoutSuccessWithoutRedirection()
    {
        $phpCas = $this->mockPhpCAS();

        $this->router
            ->expects(self::never())
            ->method('generate');

        $this->reloadConfiguration();

        self::assertFalse($this->casService->isRedirectingAfterLogout());

        $this->guardAuthenticator->onLogoutSuccess(new Request());

        $phpCas->verifyInvokedOnce('setDebug');
        $phpCas->verifyInvokedOnce('setVerbose');
        $phpCas->verifyInvokedOnce('client');
        $phpCas->verifyInvokedOnce('setLang');

        // New feature
        $phpCas->verifyNeverInvoked('logoutWithRedirectService');
        $phpCas->verifyInvokedOnce('logout');

        $phpCas->verifyNeverInvoked('setNoCasServerValidation');
        $phpCas->verifyNeverInvoked('setCasServerCACert');
        $phpCas->verifyNeverInvoked('forceAuthentication');
        $phpCas->verifyNeverInvoked('handleLogoutRequests');
        $phpCas->verifyNeverInvoked('getUser');
    }

    /**
     * Test GetDefaultSuccessRedirectUrl() method.
     *
     * @throws \ReflectionException
     */
    public function testGetDefaultSuccessRedirectUrl()
    {
        $expected = $actual = 'http://example.org/homepage';

        $this->router
            ->expects(self::once())
            ->method('generate')
            ->with('home')
            ->willReturn($actual);

        $class = new \ReflectionClass($this->guardAuthenticator);
        $method = $class->getMethod('getDefaultSuccessRedirectUrl');
        $method->setAccessible(true);

        self::assertEquals($expected, $method->invoke($this->guardAuthenticator));
    }

    /**
     * Test GetLoginUrl() method.
     *
     * @throws \ReflectionException
     */
    public function testGetLoginUrl()
    {
        $expected = $actual = 'http://example.org/login';

        $this->router
            ->expects(self::once())
            ->method('generate')
            ->with('login')
            ->willReturn($actual);

        $class = new \ReflectionClass($this->guardAuthenticator);
        $method = $class->getMethod('getLoginUrl');
        $method->setAccessible(true);

        self::assertEquals($expected, $method->invoke($this->guardAuthenticator));
    }

    /**
     * Test SupportsRememberMe() method.
     */
    public function testSupportsRememberMe()
    {
        self::assertFalse($this->guardAuthenticator->supportsRememberMe());
    }

    /**
     * Test start() method.
     */
    public function testStart()
    {
        /** @var Request|MockObject $request */
        $request = $this->getMockBuilder(Request::class)
            ->getMock();

        $request->expects(self::once())
            ->method('getUri')
            ->willReturn('foo');

        $expected = $this->casService->getUri().'foo';
        $actual = $this->guardAuthenticator->start($request, null);

        self::assertInstanceOf(RedirectResponse::class, $actual);
        self::assertEquals($expected, $actual->getTargetUrl());
    }

    /**
     * Setup the Phpunit exception before class instantiation.
     */
    public static function setUpBeforeClass()
    {
        //To fix a bug in AspectMock library.
        //In PHPUnit 6.x the PHPUnit_Framework_ExpectationFailedException was replaced by ExpectationFailedException
        if (class_exists('PHPUnit\Framework\ExpectationFailedException')) {
            class_alias('PHPUnit\Framework\ExpectationFailedException', '\PHPUnit_Framework_ExpectationFailedException');
        }

        parent::setUpBeforeClass();
    }

    /**
     * Performs assertions shared by all tests of a test case.
     *
     * This method is called before the execution of a test starts
     * and after setUp() is called.
     */
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists('phpCAS')) {
            $this->markTestSkipped('PhpCas is not present');
        }

        $this->configuration = [
            'certificate' => false,
            'debug' => 'debug.log',
            'hostname' => 'cas.example.org',
            'language' => Configuration::PHPCAS_LANG_FRENCH,
            'port' => 443,
            'route' => [
                'homepage' => 'home',
                'login' => 'login',
                'logout' => 'home',
            ],
            'verbose' => true,
            'version' => Configuration::CAS_VERSION_3_0,
            'url' => 'cas/url',
            'logout' => [
                'supported' => false,
                'handled' => false,
                'redirect_url' => false,
            ],
        ];

        $this->router = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->casService = new CasService($this->configuration);

        $this->guardAuthenticator = new CasAuthenticator(
            $this->router,
            $this->casService
        );
    }

    /**
     * Tears down the fixture and clean the double.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
        test::clean();
        $this->router = null;
        $this->casService = null;
        $this->guardAuthenticator = null;
    }

    /**
     * Using Aspect::double to Mock PhpCAS.
     *
     * What an awesome tool!
     *
     * @param array $modification
     *
     * @return Verifier
     *
     * @throws \Exception
     */
    private function mockPhpCAS(array $modification = [])
    {
        if (!isset($modification['getUser'])) {
            $modification['getUser'] = null;
        }

        test::double('phpCAS', ['setDebug' => null]);
        test::double('phpCAS', ['client' => null]);
        test::double('phpCAS', ['setLang' => null]);
        test::double('phpCAS', ['forceAuthentication' => null]);
        test::double('phpCAS', ['handleLogoutRequests' => null]);
        test::double('phpCAS', ['logout' => null]);
        test::double('phpCAS', ['setCasServerCACert' => null]);
        test::double('phpCAS', ['setNoCasServerValidation' => null]);
        test::double('phpCAS', ['setVerbose' => null]);
        $phpCas = test::double('phpCAS', ['getUser' => $modification['getUser']]);

        return $phpCas;
    }

    /**
     * An helper to reload the configuration.
     */
    private function reloadConfiguration()
    {
        $this->casService = null;
        $this->casService = new CasService($this->configuration);

        $this->guardAuthenticator = new CasAuthenticator(
            $this->router,
            $this->casService
        );
    }
}
