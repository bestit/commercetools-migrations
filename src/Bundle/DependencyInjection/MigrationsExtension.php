<?php

namespace BestIt\CommerceTools\MigrationsBundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Bundle extension
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommerceTools\MigrationsBundle\DependencyInjection
 */
class MigrationsExtension extends Extension
{
    /**
     * Load and prepare dependency injection
     *
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @return void
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('best_it.commercetools.migrations_bundle.path', $config['path']);
        $container->setParameter('best_it.commercetools.migrations_bundle.template', $config['template']);
        $container->setParameter('best_it.commercetools.migrations_bundle.container', $config['container']);
        $container->setParameter('best_it.commercetools.migrations_bundle.namespace', $config['namespace']);
        $container->setAlias('best_it.commercetools.migrations_bundle.client', $config['client']);
    }
}
