<?php

namespace BestIt\Tests\CommerceTools\Migrations;

use BestIt\CommerceTools\Migrations\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * Tests the filesystem handler
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Tests\CommerceTools\Migrations
 */
class FilesystemTest extends TestCase
{
    /**
     * Test that we find all valid migrations and sort by version asc
     *
     * @return void
     */
    public function testFindMigrations(): void
    {
        $filesystem = new Filesystem(
            __DIR__ . '/fixtures/Migrations',
            __DIR__ . '/../../src/Component/templates/migration.txt',
            'App\\Migrations'
        );

        static::assertSame([
            [
                'path' => __DIR__ . '/fixtures/Migrations/Version20180609103517.php',
                'class' => 'Version20180609103517',
                'fqcn' => 'App\Migrations\Version20180609103517',
                'version' => '20180609103517'
            ],
            [
                'path' => __DIR__ . '/fixtures/Migrations/Version20190120214950.php',
                'class' => 'Version20190120214950',
                'fqcn' => 'App\Migrations\Version20190120214950',
                'version' => '20190120214950'
            ],
            [
                'path' => __DIR__ . '/fixtures/Migrations/Version20190325195150.php',
                'class' => 'Version20190325195150',
                'fqcn' => 'App\Migrations\Version20190325195150',
                'version' => '20190325195150'
            ],
            [
                'path' => __DIR__ . '/fixtures/Migrations/Version20190405084411.php',
                'class' => 'Version20190405084411',
                'fqcn' => 'App\Migrations\Version20190405084411',
                'version' => '20190405084411'
            ],
            [
                'path' => __DIR__ . '/fixtures/Migrations/Version20190906104212.php',
                'class' => 'Version20190906104212',
                'fqcn' => 'App\Migrations\Version20190906104212',
                'version' => '20190906104212'
            ],
        ], $filesystem->findMigrations());
    }

    /**
     * Test that migration will be created
     *
     * @return void
     */
    public function testCreateMigration(): void
    {
        $filesystem = new Filesystem(
            sys_get_temp_dir(),
            __DIR__ . '/../../src/Component/templates/migration.txt',
            'App\\Migrations'
        );

        $path = $filesystem->createMigration();

        static::assertFileExists($path);
    }
}
