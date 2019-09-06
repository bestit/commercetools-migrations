<?php

namespace BestIt\Tests\CommerceTools\Migrations;

use BestIt\CommerceTools\Migrations\AbstractMigration;
use BestIt\CommerceTools\Migrations\MigrationInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests the repository
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Tests\CommerceTools\Migrations
 */
class AbstractMigrationTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface(): void
    {
        static::assertInstanceOf(
            MigrationInterface::class,
            $this->createMock(AbstractMigration::class)
        );
    }
}
