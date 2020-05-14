<?php
/**
 * This file is part of the PhpCAS Guard Bundle.
 *
 * PHP version 7.1 | 7.2
 *
 * (c) Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * @category DependencyInjection
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license   MIT
 *
 * @see https://github.com/Alexandre-T/casguard/blob/master/LICENSE
 */

namespace AlexandreT\Bundle\CasGuardBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * CAS Language English used by jasig/phpcas library.
     */
    const PHPCAS_LANG_ENGLISH = 'CAS_Languages_English';

    /**
     * CAS Language French used by jasig/phpcas library.
     */
    const PHPCAS_LANG_FRENCH = 'CAS_Languages_French';

    /**
     * CAS Language Greek used by jasig/phpcas library.
     */
    const PHPCAS_LANG_GREEK = 'CAS_Languages_Greek';

    /**
     * CAS Language German used by jasig/phpcas library.
     */
    const PHPCAS_LANG_GERMAN = 'CAS_Languages_German';

    /**
     * CAS Language Japanese used by jasig/phpcas library.
     */
    const PHPCAS_LANG_JAPANESE = 'CAS_Languages_Japanese';

    /**
     * CAS Language Spanish used by jasig/phpcas library.
     */
    const PHPCAS_LANG_SPANISH = 'CAS_Languages_Spanish';

    /**
     * CAS Language Catalan used by jasig/phpcas library.
     */
    const PHPCAS_LANG_CATALAN = 'CAS_Languages_Catalan';

    /**
     * CAS simplified chinese language used by jasig/phpcas library.
     */
    const PHPCAS_LANG_CHINESE_SIMPLIFIED = 'CAS_Languages_ChineseSimplified';

    /**
     * CAS server version 3.0.
     */
    const CAS_VERSION_3_0 = '3.0';

    /**
     * CAS server version 2.0.
     */
    const CAS_VERSION_2_0 = '2.0';

    /**
     * CAS server version 1.0.
     */
    const CAS_VERSION_1_0 = '1.0';

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        if (1 === version_compare('4.2.0', Kernel::VERSION)) {
            //Version 3.4
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('cas_guard');
        } else {
            $treeBuilder = new TreeBuilder('cas_guard');
            $rootNode = $treeBuilder->getRootNode();
        }
        $rootNode
            ->children()
                ->scalarNode('certificate')
                    ->defaultFalse()
                    ->example('certificate path')
                    ->info('Enter the certificate to identify the CAS server. Set to false if you do not use it. In production, you must use one.')
                ->end()
                ->scalarNode('debug')
                    ->defaultValue('')
                    ->example('phpcas-trace.log')
                    ->info('Enter a filename to trace log or leave empty to use the default filename. Set to false to disable debug function.')
                ->end()
                ->scalarNode('hostname')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->example('example.org')
                    ->info('Enter the hostname of the CAS server.')
                ->end()
                ->enumNode('language')
                    ->values([
                        /* @see https://github.com/apereo/phpCAS/blob/master/source/CAS.php#L215 */
                        self::PHPCAS_LANG_ENGLISH,
                        self::PHPCAS_LANG_FRENCH,
                        self::PHPCAS_LANG_GREEK,
                        self::PHPCAS_LANG_GERMAN,
                        self::PHPCAS_LANG_JAPANESE,
                        self::PHPCAS_LANG_SPANISH,
                        self::PHPCAS_LANG_CATALAN,
                        self::PHPCAS_LANG_CHINESE_SIMPLIFIED,
                    ])
                    ->defaultValue(self::PHPCAS_LANG_ENGLISH)
                    ->example('CAS_Languages_French')
                    ->info('Enter the language for phpcas error and trace. Possible value could be read here: https://github.com/apereo/phpCAS/blob/master/source/CAS.php#L215 .')
                ->end()
                ->integerNode('port')
                    ->defaultValue(443)
                    ->example('443')
                    ->info('Server cas port')
                ->end()
                ->scalarNode('url')
                    ->defaultValue('cas/login')
                    ->example('cas/login')
                    ->info('REQUEST_PATH of the CAS server.')
                ->end()
                ->arrayNode('route')
                    ->info('Route node.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('homepage')
                            ->defaultValue('homepage')
                            ->example('home')
                            ->info('Name of your home route.')
                        ->end()
                        ->scalarNode('login')
                            ->defaultValue('security_login')
                            ->example('my_login_route')
                            ->info('Name of your login route.')
                        ->end()
                        ->scalarNode('logout')
                            ->defaultValue('home')
                            ->example('home')
                            ->info('Name of the route where user is redirected after successful logout.')
                        ->end()
                    ->end()
                ->end()
                ->booleanNode('verbose')
                    ->defaultValue(false)
                    ->example('true')
                    ->info('If true phpcas trace will be more explicit.')
                ->end()
                ->enumNode('version')
                    ->values([
                        self::CAS_VERSION_3_0,
                        self::CAS_VERSION_2_0,
                        self::CAS_VERSION_1_0,
                    ])
                    ->defaultValue(self::CAS_VERSION_3_0)
                    ->example('3.0')
                    ->info('Version of the CAS Server.')
                ->end()
                ->arrayNode('logout')
                    ->info('Logout node.')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->booleanNode('supported')
                            ->defaultTrue()
                            ->example('false')
                            ->info('Are the CAS server and your application supporting single sign out signal?')
                        ->end()
                        ->booleanNode('handled')
                            ->defaultTrue()
                            ->example('false')
                            ->info('Are your application handling single sign out signal?')
                        ->end()
                        ->arrayNode('allowed_clients')
                            ->canBeUnset()
                            ->scalarPrototype()->end()
                            ->example('["server1.example.org", "server2.example.org"]')
                            ->info('List of host names allowed to send logout requests.')
                        ->end()
                        ->BooleanNode('redirect_url')
                            ->defaultFalse()
                            ->example('true')
                            ->info('true if you want to provide the url to the user to go back to your application after logout.')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
