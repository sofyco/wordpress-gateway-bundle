<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Repository;

interface SiteRepositoryInterface
{
    public function create(string $url): bool;

    public function isExists(string $url): bool;
}
