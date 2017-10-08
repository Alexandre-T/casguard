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
                        /** @see https://github.com/apereo/phpCAS/blob/master/source/CAS.php#L215 */
                        PHPCAS_LANG_ENGLISH,
                        PHPCAS_LANG_FRENCH,
                        PHPCAS_LANG_GREEK,
                        PHPCAS_LANG_GERMAN,
                        PHPCAS_LANG_JAPANESE,
                        PHPCAS_LANG_SPANISH,
                        PHPCAS_LANG_CATALAN,
                        PHPCAS_LANG_CHINESE_SIMPLIFIED,
                    ])
                    ->defaultValue(PHPCAS_LANG_ENGLISH)
                    ->example('CAS_Languages_French')
                    ->info('Enter the language for phpcas error and trace. Possible value could be read here: https://github.com/apereo/phpCAS/blob/master/source/CAS.php#L215 .')
                ->end()
                ->integerNode('port')
                    ->defaultValue(443)
                    // TODO ->example('')
                    // TODO ->info('')
                ->end()
                ->scalarNode('uri_login')
                    ->isRequired()
                    ->cannotBeEmpty()
                    // TODO ->example('')
                    // TODO ->info('')
                ->end()
                ->scalarNode('url')
                    ->defaultValue('cas/login')
                    // TODO ->example('')
                    // TODO ->info('')
                ->end()
//                ->scalarNode('ca')
//                    ->defaultNull()
//                ->end()
//                ->booleanNode('handleLogoutRequest')
//                    ->defaultValue(false)
//                ->end()
//                ->scalarNode('casLogoutTarget')
//                    ->defaultNull()
//                ->end()
//                ->booleanNode('force')
//                    ->defaultValue(true)
//                ->end()
                ->scalarNode('repository')
                    ->defaultValue('App:User')
                    // TODO ->example('')
                    // TODO ->info('')
                ->end()
                ->scalarNode('property')
                    ->defaultValue('username')
                    // TODO ->example('')
                    // TODO ->info('')
                ->end()
                ->arrayNode('route')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('homepage')
                            ->defaultValue('homepage')
                            // TODO ->example('')
                            // TODO ->info('')
                        ->end()
                        ->scalarNode('login')
                            ->defaultValue('security_login')
                            // TODO ->example('')
                            // TODO ->info('')
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
                        CAS_VERSION_3_0,
                        CAS_VERSION_2_0,
                        CAS_VERSION_1_0,
                    ])
                    ->defaultValue(CAS_VERSION_3_0)
                    // TODO ->example('')
                    // TODO ->info('')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
