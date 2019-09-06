<?php

namespace BestIt\Tests\CommerceTools\Migrations\DependencyInjection;

use BestIt\CommerceTools\MigrationsBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Test configuration
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Tests\CommerceTools\Migrations\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    /**
     * Test builder (only if a tree returned)
     *
     * @return void
     */
    public function testBuilder()
    {
        static::assertInstanceOf(TreeBuilder::class, (new Configuration())->getConfigTreeBuilder());
    }
}
