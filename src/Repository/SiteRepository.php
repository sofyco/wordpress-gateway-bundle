<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Repository;

use Pdo\Mysql;

final readonly class SiteRepository implements SiteRepositoryInterface
{
    private Mysql $connection;

    public function __construct(string $dsn)
    {
        $this->connection = new Mysql(dsn: $dsn, options: [
            Mysql::ATTR_ERRMODE => Mysql::ERRMODE_EXCEPTION,
            Mysql::ATTR_DEFAULT_FETCH_MODE => Mysql::FETCH_ASSOC,
        ]);
    }

    public function create(string $url): bool
    {
        $database = $this->getDatabaseByUrl(url: $url);

        if (!preg_match('#^[a-zA-Z0-9_]+$#', $database)) {
            throw new \InvalidArgumentException('Invalid database name');
        }

        return 1 === $this->connection->exec("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function isExists(string $url): bool
    {
        $database = $this->getDatabaseByUrl(url: $url);
        $statement = $this->connection->prepare('SELECT 1 FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ? LIMIT 1');
        $statement->execute([$database]);

        return (bool) $statement->fetchColumn();
    }

    private function getDatabaseByUrl(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST);

        if (false === is_string($domain)) {
            throw new \InvalidArgumentException('Invalid domain');
        }

        return str_replace('.', '_', $domain);
    }
}
