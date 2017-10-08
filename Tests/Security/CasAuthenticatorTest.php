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

namespace AlexandreT\Bundle\CasGuardBundle\Tests\Security;

use AlexandreT\Bundle\CasGuardBundle\Security\CasAuthenticator;
use AlexandreT\Bundle\CasGuardBundle\Service\CasService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Security\Core\User\UserInterface;
use AspectMock\Test as test;
use phpCas;

/**
 * CasAuthenticatorTest class.
 *
 * @category AlexandreT\Bundle\CasGuardBundle\Tests\Security
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
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * @var CasAuthenticator
     */
    private $guardAuthenticator;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $tokenStorage;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $casService;

    /**
     * test : Check credentials method which is always returning true.
     */
    public function testCheckCredentials()
    {
        $user = $this->getMockBuilder(UserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::assertTrue($this->guardAuthenticator->checkCredentials(null, $user));
    }

    /**
     * This is a test to validate AspectMock configuration.
     *
     * @see https://stackoverflow.com/questions/13734224/exception-serialization-of-closure-is-not-allowed
     */
    public function testAspectMock()
    {
        $phpCas = test::double('phpCAS', ['setDebug' => function () {
            echo 'YES I CALL THE MOCKED Debug function';
        }]);

        phpCAS::setDebug();
        $phpCas->verifyInvoked('setDebug', false);
        self::expectOutputString('YES I CALL THE MOCKED Debug function');
    }

    /**
     * Test the first example in phpcas documentation with a connected user (foo).
     *
     * phpCAS can be used the simplest way, as a CAS client.
     *
     * @see https://github.com/apereo/phpCAS/blob/master/docs/examples/example_simple.php
     */
    public function testExampleSimple()
    {
        $expected = $actual = 'foo';

        $this->configuration['certificate'] = 'certificate.txt'; //we use a certificate

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
        $phpCas->verifyNeverInvoked('setNoCasServerValidation');
        $phpCas->verifyInvokedOnce('forceAuthentication');
        $phpCas->verifyInvokedMultipleTimes('getUser', 2);
    }

    /**
     * Test the first example in phpcas documentation with no connected user.
     *
     * phpCAS can be used the simplest way, as a CAS client.
     *
     * @see https://github.com/apereo/phpCAS/blob/master/docs/examples/example_simple.php
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
        $phpCas->verifyInvokedMultipleTimes('getUser', 1);
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
            'language' => PHPCAS_LANG_FRENCH,
            'port' => 443,
            'verbose' => true,
            'version' => CAS_VERSION_3_0,
            'uri_login' => '/cas',
        ];

        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $this->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $this->casService = new CasService($this->configuration);

        $this->guardAuthenticator = new CasAuthenticator(
            $this->entityManager,
            $this->tokenStorage,
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
        $this->entityManager = null;
        $this->tokenStorage = null;
        $this->router = null;
        $this->casService = null;
        $this->guardAuthenticator = null;
    }

    private function mockPhpCAS(array $modification = [])
    {
        if (!isset($modification['getUser'])) {
            $modification['getUser'] = null;
        }

        test::double('phpCAS', ['setDebug' => null]);
        test::double('phpCAS', ['client' => null]);
        test::double('phpCAS', ['setLang' => null]);
        test::double('phpCAS', ['setVerbose' => null]);
        test::double('phpCAS', ['forceAuthentication' => null]);
        test::double('phpCAS', ['setCasServerCACert' => null]);
        test::double('phpCAS', ['setNoCasServerValidation' => null]);
        $phpCas = test::double('phpCAS', ['getUser' => $modification['getUser']]);

        return $phpCas;
    }

    private function reloadConfiguration()
    {
        $this->casService = null;
        $this->casService = new CasService($this->configuration);

        $this->guardAuthenticator = new CasAuthenticator(
            $this->entityManager,
            $this->tokenStorage,
            $this->router,
            $this->casService
        );
    }
}
