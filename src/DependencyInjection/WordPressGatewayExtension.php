<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\DependencyInjection;

use Sofyco\Bundle\WordPressGatewayBundle\Gateway\WordPressGateway;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepository;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepositoryInterface;
use Sofyco\Bundle\WordPressGatewayBundle\Validator\UniqueDomainValidator;
use Symfony\Component\DependencyInjection\{ContainerBuilder, Definition, Extension\Extension};

final class WordPressGatewayExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $uniqueDomainValidator = new Definition(class: UniqueDomainValidator::class);
        $uniqueDomainValidator->setAutowired(autowired: true);
        $uniqueDomainValidator->setAutoconfigured(autoconfigured: true);
        $uniqueDomainValidator->addTag(name: 'validator.constraint_validator');
        $container->setDefinition(id: UniqueDomainValidator::class, definition: $uniqueDomainValidator);

        $siteRepository = new Definition(class: SiteRepository::class);
        $siteRepository->setAutowired(autowired: true);
        $container->setDefinition(id: SiteRepositoryInterface::class, definition: $siteRepository);

        $wordPressGateway = new Definition(class: WordPressGateway::class);
        $wordPressGateway->setAutowired(autowired: true);
        $wordPressGateway->setAutoconfigured(autoconfigured: true);
        $container->setDefinition(id: WordPressGateway::class, definition: $wordPressGateway);
    }
}
