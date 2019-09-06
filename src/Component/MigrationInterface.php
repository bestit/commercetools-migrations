<?php

namespace BestIt\CommerceTools\Migrations;

use Commercetools\Core\Client;

/**
 * Interface for migrations
 *
 * @package BestIt\CommerceTools\Migrations
 */
interface MigrationInterface
{
    /**
     * Execute this migration
     *
     * @param Client $client
     *
     * @return void
     */
    public function up(Client $client): void;

    /**
     * Revert this migration
     *
     * @param Client $client
     *
     * @return void
     */
    public function down(Client $client): void;

    /**
     * Get additional description for this migration
     *
     * @return string|null
     */
    public function getDescription(): ?string;
}
