<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Connection;

use Pdo\Mysql;

class MysqlConnection extends Mysql
{
    public function __construct(string $dsn)
    {
        parent::__construct(dsn: $dsn, options: [
            self::ATTR_ERRMODE => self::ERRMODE_EXCEPTION,
            self::ATTR_DEFAULT_FETCH_MODE => self::FETCH_ASSOC,
        ]);
    }
}
