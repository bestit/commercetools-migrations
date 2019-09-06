<?php

namespace BestIt\CommerceTools\Migrations;

use Commercetools\Core\Client;
use Commercetools\Core\Error\ApiException;
use Commercetools\Core\Error\InvalidTokenException;
use Commercetools\Core\Model\CustomObject\CustomObject;
use Commercetools\Core\Model\CustomObject\CustomObjectDraft;
use Commercetools\Core\Request\CustomObjects\CustomObjectCreateRequest;
use Commercetools\Core\Request\CustomObjects\CustomObjectQueryRequest;
use Commercetools\Core\Response\PagedQueryResponse;

/**
 * Repository for handle migrations states
 *
 * @package BestIt\CommerceTools\Migrations
 */
class Repository
{
    /** @var Client */
    private $client;

    /** @var string */
    private $container;

    /**
     * Repository constructor.
     *
     * @param Client $client
     * @param string $container
     */
    public function __construct(Client $client, string $container)
    {
        $this->client = $client;
        $this->container = $container;
    }

    /**
     * Get all executed migrations
     *
     * @return array
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    public function getMigrations(): array
    {
        $result = [];
        $lastId = null;
        $limit = 500;
        $baseQuery = sprintf('container="%s"', $this->container);

        do {
            $criteria = [$baseQuery];

            if ($lastId) {
                $criteria[] = sprintf('id > "%s"', $lastId);
            }

            $request = new CustomObjectQueryRequest();
            $request->where(implode(' and ', $criteria));
            $request->sort('id asc');
            $request->limit($limit);
            $request->offset(0);

            /** @var PagedQueryResponse $response */
            $response = $this->client->execute($request);
            $collection = $response->toObject();

            /** @var CustomObject $object */
            foreach ($collection as $object) {
                $lastId = $object->getId();
                $result[] = $object->getKey();
            }
        } while ($collection->count() === $limit);

        return $result;
    }

    /**
     * Add new migration
     *
     * @param string $version
     * @param string $filename
     * @param MigrationInterface $migration
     *
     * @return void
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    public function addMigration(string $version, string $filename, MigrationInterface $migration): void
    {
        $request = CustomObjectCreateRequest::ofDraft(
            CustomObjectDraft::ofContainerKeyAndValue($this->container, $version, [
                'filename' => $filename,
                'description' => $migration->getDescription()
            ])
        );

        $this->client->execute($request);
    }
}
