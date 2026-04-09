<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Entity;

interface ContentInterface
{
    public string $id {
        get;
    }

    public string $image {
        get;
    }

    public string $title {
        get;
    }

    public string $description {
        get;
    }

    public string $content {
        get;
    }

    /**
     * @var string[]
     */
    public iterable $tags {
        get;
    }

    /**
     * @var string[]
     */
    public iterable $categories {
        get;
    }
}
