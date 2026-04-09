<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\DependencyInjection;

use Sofyco\Bundle\WordPressGatewayBundle\Validator\UniqueDomainValidator;
use Symfony\Component\DependencyInjection\{ContainerBuilder, Definition, Extension\Extension};

final class WordPressGatewayExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $definition = new Definition(class: UniqueDomainValidator::class);
        $definition->setAutowired(autowired: true);
        $definition->setAutoconfigured(autoconfigured: true);
        $definition->addTag(name: 'validator.constraint_validator');
        $container->setDefinition(id: UniqueDomainValidator::class, definition: $definition);
    }
}
