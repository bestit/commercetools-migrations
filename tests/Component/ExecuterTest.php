<?php

namespace BestIt\Tests\CommerceTools\Migrations;

use BestIt\CommerceTools\Migrations\Executer;
use BestIt\CommerceTools\Migrations\MigrationInterface;
use Commercetools\Core\Client;
use PHPUnit\Framework\TestCase;

/**
 * Tests the executer
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Tests\CommerceTools\Migrations
 */
class ExecuterTest extends TestCase
{
    /**
     * Test apply
     *
     * @return void
     */
    public function testApply(): void
    {
        $executer = new Executer(
            $client = $this->createMock(Client::class)
        );

        $migration = $this->createMock(MigrationInterface::class);
        $migration
            ->expects(static::once())
            ->method('up')
            ->with($client);

        $executer->apply($migration);
    }
}
