<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Repository;

interface DatabaseRepositoryInterface
{
    public function create(string $database): bool;

    public function isExists(string $database): bool;
}
