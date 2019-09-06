<?php

declare(strict_types=1);

namespace BestIt\CommerceTools\MigrationsBundle\Command;

use BestIt\CommerceTools\Migrations\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new and empty migration file
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommerceTools\MigrationsBundle\Command
 */
class CreateCommand extends Command
{
    /** @var Filesystem */
    private $filesystem;

    /**
     * CreateCommand constructor.
     *
     * @param Filesystem $migrator
     */
    public function __construct(Filesystem $migrator)
    {
        $this->filesystem = $migrator;

        parent::__construct();
    }

    /**
     * Configures this command.
     *
     * @throws InvalidArgumentException When the name is invalid.
     *
     * @return void
     */
    public function configure(): void
    {
        $this
            ->setName('commercetools:migrations:create')
            ->setDescription('Create new and empty migration file.');
    }

    /**
     * Create a new file
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $logger = new ConsoleLogger($output);
        $this->filesystem->setLogger($logger);

        $this->filesystem->createMigration();
    }
}
