<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Entity;

final readonly class PublishPostContent
{
    public function __construct(public string $id, public ?string $image, public string $title, public string $description, public string $content, public iterable $tags, public iterable $categories)
    {
    }
}
