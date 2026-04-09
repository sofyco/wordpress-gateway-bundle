<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Entity;

final readonly class SiteInstallation
{
    public function __construct(public string $baseUrl, public array $configuration)
    {
    }
}
