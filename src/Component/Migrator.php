<?php

namespace BestIt\CommerceTools\Migrations;

use Commercetools\Core\Error\ApiException;
use Commercetools\Core\Error\InvalidTokenException;
use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Manage migrations
 *
 * @package BestIt\CommerceTools\Migrations
 */
class Migrator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var Filesystem */
    private $filesystem;

    /** @var Repository */
    private $repository;

    /** @var Executer */
    private $executer;

    /**
     * Migrator constructor.
     *
     * @param Filesystem $filesystem
     * @param Repository $repository
     * @param Executer $executer
     */
    public function __construct(Filesystem $filesystem, Repository $repository, Executer $executer)
    {
        $this->filesystem = $filesystem;
        $this->repository = $repository;
        $this->executer = $executer;

        $this->setLogger(new NullLogger());
    }

    /**
     * Migrate open tasks / currently only "up" is supported
     *
     * @param bool $dryRun
     *
     * @return void
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    public function migrate(bool $dryRun): void
    {
        $executedVersions = $this->repository->getMigrations();

        foreach ($this->filesystem->findMigrations() as $file) {
            if (!in_array($file['version'], $executedVersions, true)) {
                $this->logger->info(sprintf('Found new migration: %s', $file['version']));

                $migration = $this->createMigration($file['fqcn'], $file['version']);

                if ($dryRun) {
                    $this->logger->info(sprintf('Would apply: %s', $file['version']));
                } else {
                    $this->logger->info(sprintf('Apply: %s', $file['version']));
                    $this->executer->apply($migration);

                    $this->logger->info(sprintf('Mark: %s', $file['version']));
                    $this->repository->addMigration($file['version'], $file['class'], $migration);
                }
            }
        }
    }

    /**
     * Create migration
     *
     * @param string $fqcl
     * @param string $version
     *
     * @return MigrationInterface
     */
    private function createMigration(string $fqcl, string $version): MigrationInterface
    {
        $migration = new $fqcl();

        if (!$migration instanceof MigrationInterface) {
            throw new InvalidArgumentException(
                sprintf('Migration version `%s` does not implement migration interface ', $version)
            );
        }

        return $migration;
    }
}
