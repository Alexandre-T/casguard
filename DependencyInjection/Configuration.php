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
                ->booleanNode('verbose')
                    ->defaultValue(false)
                    ->example('true')
                    ->info('If true phpcas trace will be more explicit.')
                ->end()
                ->scalarNode('hostname')
                    ->isRequired()
                    ->cannotBeEmpty()
                    //->example( TODO complete it )
                    //->info( TODO complete it )
                ->end()
                ->integerNode('port')
                    ->defaultValue(443)
                    //->example( TODO complete it )
                    //->info( TODO complete it )
                ->end()
                ->scalarNode('uri_login')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('url')
                    ->defaultValue('cas/login')
                ->end()
                ->enumNode('version')
                    ->values([
                        CAS_VERSION_3_0,
                        CAS_VERSION_2_0,
                        CAS_VERSION_1_0,
                    ])
                    ->defaultValue(CAS_VERSION_3_0)
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
                ->end()
                ->scalarNode('property')
                    ->defaultValue('username')
                ->end()
                ->arrayNode('route')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('homepage')
                            ->defaultValue('homepage')
                        ->end()
                        ->scalarNode('login')
                            ->defaultValue('security_login')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
