<?php

declare(strict_types=1);

namespace BestIt\CommerceTools\MigrationsBundle\Command;

use BestIt\CommerceTools\Migrations\Migrator;
use Commercetools\Core\Error\ApiException;
use Commercetools\Core\Error\InvalidTokenException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Migrate versions
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommerceTools\MigrationsBundle\Command
 */
class MigrateCommand extends Command
{
    /** @var Migrator */
    private $migrator;

    /**
     * MigrateCommand constructor.
     *
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;

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
            ->setName('commercetools:migrations:migrate')
            ->setDescription('Migrate versions.')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Execute with dry run.');
    }

    /**
     * Migrate versions
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $dryRun = $input->getOption('dry-run');

        $logger = new ConsoleLogger($output);
        $this->migrator->setLogger($logger);

        $this->migrator->migrate($dryRun);
    }
}
