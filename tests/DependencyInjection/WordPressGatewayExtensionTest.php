<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\WordPressGatewayBundle\DependencyInjection\WordPressGatewayExtension;
use Sofyco\Bundle\WordPressGatewayBundle\Gateway\WordPressGateway;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepository;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepositoryInterface;
use Sofyco\Bundle\WordPressGatewayBundle\Validator\UniqueDomainValidator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class WordPressGatewayExtensionTest extends TestCase
{
    public function testLoadRegistersDefinitions(): void
    {
        $container = new ContainerBuilder();
        $extension = new WordPressGatewayExtension();

        $extension->load(configs: [], container: $container);

        self::assertTrue($container->hasDefinition(UniqueDomainValidator::class));
        self::assertTrue($container->hasDefinition(SiteRepositoryInterface::class));
        self::assertTrue($container->hasDefinition(WordPressGateway::class));

        $validatorDefinition = $container->getDefinition(UniqueDomainValidator::class);
        self::assertSame(UniqueDomainValidator::class, $validatorDefinition->getClass());
        self::assertTrue($validatorDefinition->isAutowired());
        self::assertTrue($validatorDefinition->isAutoconfigured());
        self::assertTrue($validatorDefinition->hasTag('validator.constraint_validator'));

        $siteRepositoryDefinition = $container->getDefinition(SiteRepositoryInterface::class);
        self::assertSame(SiteRepository::class, $siteRepositoryDefinition->getClass());
        self::assertTrue($siteRepositoryDefinition->isAutowired());

        $wordPressGatewayDefinition = $container->getDefinition(WordPressGateway::class);
        self::assertSame(WordPressGateway::class, $wordPressGatewayDefinition->getClass());
        self::assertTrue($wordPressGatewayDefinition->isAutowired());
        self::assertTrue($wordPressGatewayDefinition->isAutoconfigured());
    }
}
