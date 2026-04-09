<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Repository;

use Pdo\Mysql;

final class MysqlConnection extends Mysql implements DatabaseRepositoryInterface
{
    public function __construct(string $dsn)
    {
        parent::__construct(dsn: $dsn, options: [
            self::ATTR_ERRMODE => self::ERRMODE_EXCEPTION,
            self::ATTR_DEFAULT_FETCH_MODE => self::FETCH_ASSOC,
        ]);
    }

    public function create(string $database): bool
    {
        if (false === preg_match('#^[a-zA-Z0-9_]+$#', $database)) {
            throw new \InvalidArgumentException('Invalid database name');
        }

        return 1 === $this->exec("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function isExists(string $database): bool
    {
        $statement = $this->prepare('SELECT 1 FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ? LIMIT 1');
        $statement->execute([$database]);

        return (bool) $statement->fetchColumn();
    }
}
