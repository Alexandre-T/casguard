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

namespace AlexandreT\Bundle\CasGuardBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    const PHPCAS_LANG_ENGLISH = 'CAS_Languages_English';
    const PHPCAS_LANG_FRENCH = 'CAS_Languages_French';
    const PHPCAS_LANG_GREEK = 'CAS_Languages_Greek';
    const PHPCAS_LANG_GERMAN = 'CAS_Languages_German';
    const PHPCAS_LANG_JAPANESE = 'CAS_Languages_Japanese';
    const PHPCAS_LANG_SPANISH = 'CAS_Languages_Spanish';
    const PHPCAS_LANG_CATALAN = 'CAS_Languages_Catalan';
    const PHPCAS_LANG_CHINESE_SIMPLIFIED = 'CAS_Languages_ChineseSimplified';

    const CAS_VERSION_3_0 = '3.0';
    const CAS_VERSION_2_0 = '2.0';
    const CAS_VERSION_1_0 = '1.0';

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('phpcas_guard');
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
                    ->defaultValue(PHPCAS_LANG_ENGLISH)
                    ->example('CAS_Languages_French')
                    ->info('Enter the language for phpcas error and trace. Possible value could be read here: https://github.com/apereo/phpCAS/blob/master/source/CAS.php#L215 .')
                ->end()
                ->integerNode('port')
                    ->defaultValue(443)
                    ->example('443')
                    ->info('Server cas port')
                ->end()
                //FIXME Destroy this parameter and calculate ir
                ->scalarNode('uri_login')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->example('https://www.example.org:443/cas/login')
                    ->info('Complete URL of the cas server login page')
                ->end()
                ->scalarNode('url')
                    ->defaultValue('cas/login')
                    ->example('cas/login')
                    ->info('REQUEST_PATH of the CAS server')
                ->end()
                ->scalarNode('repository')
                    ->defaultValue('App:User')
                    ->example('AppBundle:User')
                    ->info('Repository to retrieve user.')
                ->end()
                ->scalarNode('property')
                    ->defaultValue('username')
                    ->example('mail')
                    ->info('property of the repository to compare with provided credentials (username or email)')
                ->end()
                ->arrayNode('route')
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
                    ->addDefaultsIfNotSet()
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
                        ->VariableNode('allowed_clients')
                            ->example('["server1.example.org", "server2.example.org"]')
                            ->info('An array of host names allowed to send logout requests.')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
