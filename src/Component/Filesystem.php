<?php

namespace BestIt\CommerceTools\Migrations;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\Finder\Finder;

/**
 * Handle all actions with files
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommerceTools\Migrations
 */
class Filesystem implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var string */
    private $path;

    /** @var string */
    private $template;

    /** @var string */
    private $namespace;

    /**
     * Filesystem constructor.
     *
     * @param string $path
     * @param string $template
     * @param string $namespace
     */
    public function __construct(string $path, string $template, string $namespace)
    {
        $this->path = $path;
        $this->template = $template;
        $this->namespace = $namespace;

        $this->setLogger(new NullLogger());
    }

    /**
     * Create a file and return file path
     *
     * @return string
     */
    public function createMigration(): string
    {
        $class = 'Version' . date('Ymdhis');

        $body = file_get_contents($this->template);
        $body = str_replace('{CLASS_NAME}', $class, $body);
        $body = str_replace('{NAMESPACE_NAME}', $this->namespace, $body);

        $file = sprintf('%s/%s.php', $this->path, $class);
        file_put_contents($file, $body);

        $this->logger->info(sprintf('New migration file created: `%s`', $file));

        return $file;
    }

    /**
     * Find migrations
     *
     * @return array
     */
    public function findMigrations(): array
    {
        $result = [];

        $finder = new Finder();
        $finder->files()->in($this->path);
        foreach ($finder as $file) {
            if (preg_match('/^(Version(\d+))\.php$/', $file->getFilename(), $matches) === 1) {
                $result[] = [
                    'path' => $file->getRealPath(),
                    'class' => $matches[1],
                    'fqcn' => $this->namespace . '\\' . $matches[1],
                    'version' => $matches[2]
                ];
            }
        }

        usort($result, function (array $a, array $b) {
            return $a['version'] <=> $b['version'];
        });

        return $result;
    }
}
