<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\WordPressGatewayBundle\Entity\PublishPostContent;

final class PublishPostContentTest extends TestCase
{
    public function testItStoresConstructorValues(): void
    {
        $content = new PublishPostContent(
            id: 'id-1',
            image: 'https://example.com/image.jpg',
            title: 'Title',
            description: 'Description',
            content: '<p>Body</p>',
            tags: ['tag1', 'tag2'],
            categories: ['category'],
        );

        self::assertSame('id-1', $content->id);
        self::assertSame('https://example.com/image.jpg', $content->image);
        self::assertSame('Title', $content->title);
        self::assertSame('Description', $content->description);
        self::assertSame('<p>Body</p>', $content->content);
        self::assertSame(['tag1', 'tag2'], $content->tags);
        self::assertSame(['category'], $content->categories);
    }
}
