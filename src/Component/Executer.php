<?php

namespace BestIt\CommerceTools\Migrations;

use Commercetools\Core\Client;
use InvalidArgumentException;

/**
 * Execute migrations
 *
 * @package BestIt\CommerceTools\Migrations
 */
class Executer
{
    /** @var Client */
    private $client;

    /**
     * Executer constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Apply a migration
     *
     * @param MigrationInterface $migration
     *
     * @return void
     */
    public function apply(MigrationInterface $migration): void
    {
        $migration->up($this->client);
    }
}
