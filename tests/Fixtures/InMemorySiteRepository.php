<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\Fixtures;

use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepositoryInterface;

final class InMemorySiteRepository implements SiteRepositoryInterface
{
    /** @var array<int, string> */
    public array $existingUrls = [];

    public function create(string $url): bool
    {
        return true;
    }

    public function isExists(string $url): bool
    {
        return in_array($url, $this->existingUrls, true);
    }
}
