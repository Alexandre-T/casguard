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
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('phpcas_guard');
        $rootNode
            ->children()
            ->scalarNode('hostname')->defaultValue('')->end()
            ->scalarNode('login')->defaultValue('')->end()
            ->scalarNode('port')->defaultValue(443)->end()
            ->scalarNode('url')->defaultValue('cas/login')->end()
            ->scalarNode('version')->defaultValue('3.0')->end()
//            ->scalarNode('ca')->defaultNull()->end()
//            ->booleanNode('handleLogoutRequest')->defaultValue(false)->end()
//            ->scalarNode('casLogoutTarget')->defaultNull()->end()
//            ->booleanNode('force')->defaultValue(true)->end()
            ->end();
        return $treeBuilder;
    }
}