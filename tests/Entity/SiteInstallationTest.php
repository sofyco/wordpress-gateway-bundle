<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\WordPressGatewayBundle\Entity\SiteInstallation;

final class SiteInstallationTest extends TestCase
{
    public function testItStoresConstructorValues(): void
    {
        $site = new SiteInstallation(
            baseUrl: 'https://example.com',
            configuration: ['admin_user' => 'admin'],
        );

        self::assertSame('https://example.com', $site->baseUrl);
        self::assertSame(['admin_user' => 'admin'], $site->configuration);
    }
}
