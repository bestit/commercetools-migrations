<?php

namespace BestIt\Tests\CommerceTools\Migrations;

use BestIt\CommerceTools\Migrations\MigrationInterface;
use BestIt\CommerceTools\Migrations\Repository;
use Commercetools\Core\Client;
use Commercetools\Core\Error\ApiException;
use Commercetools\Core\Error\InvalidTokenException;
use Commercetools\Core\Model\Common\Collection;
use Commercetools\Core\Model\CustomObject\CustomObject;
use Commercetools\Core\Model\CustomObject\CustomObjectDraft;
use Commercetools\Core\Request\CustomObjects\CustomObjectCreateRequest;
use Commercetools\Core\Request\CustomObjects\CustomObjectQueryRequest;
use Commercetools\Core\Response\PagedQueryResponse;
use PHPUnit\Framework\TestCase;

/**
 * Tests the repository
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Tests\CommerceTools\Migrations
 */
class RepositoryTest extends TestCase
{
    /**
     * Test get migrations from CommerceTools
     *
     * @return void
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    public function testGetMigrations(): void
    {
        $repository = new Repository(
            $client = $this->createMock(Client::class),
            'migrations'
        );

        $expectedRequest1 = new CustomObjectQueryRequest();
        $expectedRequest1->where('container="migrations"');
        $expectedRequest1->sort('id asc');
        $expectedRequest1->limit(500);
        $expectedRequest1->offset(0);

        $expectedRequest2 = new CustomObjectQueryRequest();
        $expectedRequest2->where('container="migrations" and id > "foo499"');
        $expectedRequest2->sort('id asc');
        $expectedRequest2->limit(500);
        $expectedRequest2->offset(0);

        $expectedRequest3 = new CustomObjectQueryRequest();
        $expectedRequest3->where('container="migrations" and id > "bar499"');
        $expectedRequest3->sort('id asc');
        $expectedRequest3->limit(500);
        $expectedRequest3->offset(0);

        $client
            ->expects(static::exactly(3))
            ->method('execute')
            ->withConsecutive([$expectedRequest1], [$expectedRequest2], [$expectedRequest3])
            ->willReturnOnConsecutiveCalls(
                $response1 = $this->createMock(PagedQueryResponse::class),
                $response2 = $this->createMock(PagedQueryResponse::class),
                $response3 = $this->createMock(PagedQueryResponse::class)
            );

        $response1->method('toObject')->willReturn($collection1 = new Collection());
        $expectedVersions = [];
        for ($i = 0, $iMax = 500; $i < $iMax; $i++) {
            $expectedVersions[] = $version = uniqid();
            $collection1->add(new CustomObject(['id' => 'foo' . $i, 'key' => $version]));
        }

        $response2->method('toObject')->willReturn($collection2 = new Collection());
        for ($i = 0, $iMax = 500; $i < $iMax; $i++) {
            $expectedVersions[] = $version = uniqid();
            $collection2->add(new CustomObject(['id' => 'bar'. $i, 'key' => $version]));
        }

        $response3->method('toObject')->willReturn($collection3 = new Collection());

        static::assertEquals($expectedVersions, $repository->getMigrations());
    }

    /**
     * Test add migration
     *
     * @return void
     *
     * @throws ApiException
     * @throws InvalidTokenException
     */
    public function testAddMigration(): void
    {
        $repository = new Repository(
            $client = $this->createMock(Client::class),
            'migrations'
        );

        $version = uniqid();
        $filename = uniqid();
        $description = uniqid();

        $migration = $this->createMock(MigrationInterface::class);
        $migration
            ->expects(static::once())
            ->method('getDescription')
            ->willReturn($description);

        $expectedRequest = CustomObjectCreateRequest::ofDraft(
            CustomObjectDraft::ofContainerKeyAndValue('migrations', $version, [
                'filename' => $filename,
                'description' => $description
            ])
        );

        $client
            ->expects(static::once())
            ->method('execute')
            ->with($expectedRequest);

        $repository->addMigration($version, $filename, $migration);
    }
}
