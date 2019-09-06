<?php

namespace BestIt\Tests\CommerceTools\Migrations;

use BestIt\Tests\CommerceTools\Migrations\fixtures\Migrations as Fixture;
use BestIt\CommerceTools\Migrations\Executer;
use BestIt\CommerceTools\Migrations\Filesystem;
use BestIt\CommerceTools\Migrations\Migrator;
use BestIt\CommerceTools\Migrations\Repository;
use Commercetools\Core\Error\ApiException;
use Commercetools\Core\Error\InvalidTokenException;
use PHPUnit\Framework\TestCase;

/**
 * Tests the migrator
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Tests\CommerceTools\Migrations
 */
class MigratorTest extends TestCase
{
    /**
     * Test migrate
     *
     * @return void
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    public function testMigrate(): void
    {
        $filesystem = new Filesystem(
            __DIR__ . '/fixtures/Migrations',
            __DIR__ . '/../../src/Component/templates/migration.txt',
            'BestIt\\Tests\\CommerceTools\\Migrations\\fixtures\\Migrations'
        );

        $migrator = new Migrator(
            $filesystem,
            $repository = $this->createMock(Repository::class),
            $executer = $this->createMock(Executer::class)
        );

        $repository
            ->expects(static::once())
            ->method('getMigrations')
            ->willReturn([
                '20180609103517',
                '20190120214950',
                '20190325195150'
            ]);

        $executer
            ->expects(static::exactly(2))
            ->method('apply')
            ->withConsecutive(
                [static::isInstanceOf(Fixture\Version20190405084411::class)],
                [static::isInstanceOf(Fixture\Version20190906104212::class)]
            );

        $repository
            ->expects(static::exactly(2))
            ->method('addMigration')
            ->withConsecutive(
                [
                    '20190405084411',
                    'Version20190405084411',
                    static::isInstanceOf(Fixture\Version20190405084411::class)
                ],
                [
                    '20190906104212',
                    'Version20190906104212',
                    static::isInstanceOf(Fixture\Version20190906104212::class)
                ]
            );

        $migrator->migrate(false);
    }

    /**
     * Test migrate with dry run
     *
     * @return void
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    public function testMigrateDryRun(): void
    {
        $filesystem = new Filesystem(
            __DIR__ . '/fixtures/Migrations',
            __DIR__ . '/../../src/Component/templates/migration.txt',
            'BestIt\\Tests\\CommerceTools\\Migrations\\fixtures\\Migrations'
        );

        $migrator = new Migrator(
            $filesystem,
            $repository = $this->createMock(Repository::class),
            $executer = $this->createMock(Executer::class)
        );

        $repository
            ->expects(static::once())
            ->method('getMigrations')
            ->willReturn([
                '20180609103517',
                '20190120214950',
                '20190325195150'
            ]);

        $executer
            ->expects(static::never())
            ->method('apply');

        $repository
            ->expects(static::never())
            ->method('addMigration');

        $migrator->migrate(true);
    }
}
