<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Tests\Gateway;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sofyco\Bundle\WordPressGatewayBundle\Entity\Content;
use Sofyco\Bundle\WordPressGatewayBundle\Entity\Site;
use Sofyco\Bundle\WordPressGatewayBundle\Gateway\WordPressGateway;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class WordPressGatewayTest extends TestCase
{
    private MockObject & HttpClientInterface $httpClient;
    private MockObject & SiteRepositoryInterface $siteRepository;
    private WordPressGateway $gateway;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->siteRepository = $this->createMock(SiteRepositoryInterface::class);
        $this->gateway = new WordPressGateway($this->httpClient, $this->siteRepository);
    }

    public function testIsSiteExistsDelegatesUrlToRepository(): void
    {
        $this->httpClient->expects($this->never())->method('request');
        $this->siteRepository
            ->expects($this->once())
            ->method('isExists')
            ->with('https://sub.example.com/path')
            ->willReturn(true);

        self::assertTrue($this->gateway->isSiteExists('https://sub.example.com/path'));
    }

    public function testIsSiteExistsPropagatesInvalidDomainFromRepository(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid domain');
        $this->httpClient->expects($this->never())->method('request');
        $this->siteRepository
            ->expects($this->once())
            ->method('isExists')
            ->with('not-a-url')
            ->willThrowException(new \InvalidArgumentException('Invalid domain'));

        $this->gateway->isSiteExists('not-a-url');
    }

    public function testCreateSiteReturnsTrueOnOkStatus(): void
    {
        $site = new Site('https://example.com', ['admin_user' => 'admin']);
        $response = $this->createMock(ResponseInterface::class);

        $this->siteRepository
            ->expects($this->once())
            ->method('create')
            ->with('https://example.com')
            ->willReturn(true);
        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_POST,
                'https://example.com/wp-admin/install.php',
                [
                    'json' => ['admin_user' => 'admin'],
                    'verify_peer' => false,
                ],
            )
            ->willReturn($response);

        self::assertTrue($this->gateway->createSite($site));
    }

    public function testCreateSiteReturnsFalseOnNonOkStatus(): void
    {
        $site = new Site('https://example.com', ['admin_user' => 'admin']);
        $response = $this->createMock(ResponseInterface::class);

        $this->siteRepository->expects($this->once())->method('create')->with('https://example.com')->willReturn(true);
        $response->expects($this->once())->method('getStatusCode')->willReturn(Response::HTTP_CREATED);
        $this->httpClient->expects($this->once())->method('request')->willReturn($response);

        self::assertFalse($this->gateway->createSite($site));
    }

    public function testPublishPostSendsPayloadAndReturnsArray(): void
    {
        $this->siteRepository->expects($this->never())->method('create');
        $this->siteRepository->expects($this->never())->method('isExists');
        $content = new Content(
            id: '42',
            image: 'https://example.com/image.jpg',
            title: 'Title',
            description: 'Description',
            content: '<p>Content</p>',
            tags: ['tag1', 'tag2'],
            categories: ['cat1'],
        );
        $response = $this->createMock(ResponseInterface::class);

        $response->expects($this->once())->method('toArray')->willReturn(['id' => 42, 'status' => 'published']);
        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_POST,
                'https://example.com/wp-json/evdim/posts',
                [
                    'json' => [
                        'id' => '42',
                        'image' => 'https://example.com/image.jpg',
                        'title' => 'Title',
                        'description' => 'Description',
                        'content' => '<p>Content</p>',
                        'tags' => ['tag1', 'tag2'],
                        'categories' => ['cat1'],
                        'accessToken' => 'token',
                    ],
                    'verify_peer' => false,
                ],
            )
            ->willReturn($response);

        self::assertSame(
            ['id' => 42, 'status' => 'published'],
            $this->gateway->publishPost('token', 'https://example.com', $content),
        );
    }
}
