<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\Repository;

use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\WordPressGatewayBundle\Connection\MysqlConnection;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepository;

final class SiteRepositoryTest extends TestCase
{
    public function testCreateThrowsOnInvalidDatabaseName(): void
    {
        $connection = $this->createMock(MysqlConnection::class);
        $connection->expects($this->never())->method('exec');
        $repository = $this->createMock(SiteRepository::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid database name');

        $repository->create('https://my-site.com');
    }

    public function testCreateExecutesSqlAndReturnsTrue(): void
    {
        $connection = $this->createMock(MysqlConnection::class);
        $connection
            ->expects($this->once())
            ->method('exec')
            ->with('CREATE DATABASE `example_com` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci')
            ->willReturn(1);
        $repository = $this->createMock(SiteRepository::class);

        self::assertTrue($repository->create('https://example.com'));
    }

    public function testIsExistsReturnsTrueWhenSchemaFound(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())->method('execute')->with(['example_com']);
        $statement->expects($this->once())->method('fetchColumn')->willReturn('1');

        $connection = $this->createMock(MysqlConnection::class);
        $connection
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT 1 FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ? LIMIT 1')
            ->willReturn($statement);
        $repository = $this->createMock(SiteRepository::class);

        self::assertTrue($repository->isExists('https://example.com'));
    }

    public function testIsExistsReturnsFalseWhenSchemaMissing(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())->method('execute')->with(['missing_schema']);
        $statement->expects($this->once())->method('fetchColumn')->willReturn(false);

        $connection = $this->createMock(MysqlConnection::class);
        $connection->expects($this->once())->method('prepare')->willReturn($statement);
        $repository = $this->createMock(SiteRepository::class);

        self::assertFalse($repository->isExists('https://missing.schema'));
    }

    public function testIsExistsThrowsOnInvalidUrl(): void
    {
        $connection = $this->createMock(MysqlConnection::class);
        $connection->expects($this->never())->method('prepare');
        $repository = $this->createMock(SiteRepository::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid domain');

        $repository->isExists('not-a-url');
    }
}
