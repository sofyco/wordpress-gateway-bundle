<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\WordPressGatewayBundle\DependencyInjection\WordPressGatewayExtension;
use Sofyco\Bundle\WordPressGatewayBundle\WordPressGatewayBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class WordPressGatewayBundleTest extends TestCase
{
    public function testBundleExtendsSymfonyBundle(): void
    {
        $bundle = new WordPressGatewayBundle();

        self::assertInstanceOf(Bundle::class, $bundle);
    }

    public function testBundleProvidesContainerExtension(): void
    {
        $bundle = new WordPressGatewayBundle();
        $extension = $bundle->getContainerExtension();

        self::assertInstanceOf(WordPressGatewayExtension::class, $extension);
        self::assertSame('word_press_gateway', $extension->getAlias());
    }
}
