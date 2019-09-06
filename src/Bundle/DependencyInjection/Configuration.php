<?php

namespace BestIt\CommerceTools\MigrationsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for this bundle
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommerceTools\MigrationsBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Get config tree
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('migrations_bundle');
        $rootNode = $this->getRootNode($treeBuilder, 'migrations_bundle');

        $rootNode
            ->children()
                ->scalarNode('client')
                    ->info('Please provide a CommerceTools client.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('path')
                    ->info('Please provide path for migrations.')
                    ->cannotBeEmpty()
                    ->isRequired()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($value) {
                            return rtrim($value, '\\/');
                        })
                    ->end()
                ->end()
                ->scalarNode('namespace')
                    ->info('The namespace for your migration files.')
                    ->defaultValue('App\\Migrations')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('container')
                    ->info('The container name for saving executed migrations.')
                    ->defaultValue('migrations')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('template')
                    ->info('Template to use for new migrations.')
                    ->defaultValue(__DIR__ . '/../../Component/templates/migration.txt')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * BC layer for symfony/config 4.1 and older
     *
     * @param TreeBuilder $treeBuilder
     * @param string $name
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    private function getRootNode(TreeBuilder $treeBuilder, string $name): NodeDefinition
    {
        if (!\method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->root($name);
        }

        return $treeBuilder->getRootNode();
    }
}
