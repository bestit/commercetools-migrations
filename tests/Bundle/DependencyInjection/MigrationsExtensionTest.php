<?php

namespace BestIt\Tests\CommerceTools\Migrations\DependencyInjection;

use BestIt\CommerceTools\MigrationsBundle\DependencyInjection\MigrationsExtension;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Tests the bundle extension
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Tests\CommerceTools\Migrations\DependencyInjection
 */
class MigrationsExtensionTest extends TestCase
{
    /**
     * The container
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $container = new ContainerBuilder();
        new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../src/Resources/config'));

        $this->container = $container;
    }

    /**
     * Test complete load
     *
     * @return void
     *
     * @throws Exception
     */
    public function testLoad()
    {
        $config = [
            [
                'path' => $path = uniqid(),
                'template' => $template = uniqid(),
                'container' => $containerName = uniqid(),
                'namespace' => $namespace = uniqid(),
                'client' => $client = uniqid(),
            ]
        ];

        $extension = new MigrationsExtension();
        $extension->load($config, $container = new ContainerBuilder());

        static::assertEquals($path, $container->getParameter('best_it.commercetools.migrations_bundle.path'));
        static::assertEquals($template, $container->getParameter('best_it.commercetools.migrations_bundle.template'));
        static::assertEquals($containerName, $container->getParameter('best_it.commercetools.migrations_bundle.container'));
        static::assertEquals($namespace, $container->getParameter('best_it.commercetools.migrations_bundle.namespace'));
        static::assertTrue($container->hasAlias('best_it.commercetools.migrations_bundle.client'));
    }
}
