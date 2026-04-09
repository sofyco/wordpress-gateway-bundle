<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Gateway;

use Sofyco\Bundle\WordPressGatewayBundle\Entity\Content;
use Sofyco\Bundle\WordPressGatewayBundle\Entity\Site;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\SiteRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class WordPressGateway
{
    public function __construct(private HttpClientInterface $httpClient, private SiteRepositoryInterface $siteRepository)
    {
    }

    public function isSiteExists(string $baseUrl): bool
    {
        return $this->siteRepository->isExists(url: $baseUrl);
    }

    public function createSite(Site $site): bool
    {
        $this->siteRepository->create(url: $site->baseUrl);

        $response = $this->httpClient->request(
            method: Request::METHOD_POST,
            url: $site->baseUrl . '/wp-admin/install.php',
            options: [
                'json' => $site->configuration,
                'verify_peer' => false,
            ],
        );

        return Response::HTTP_OK === $response->getStatusCode();
    }

    public function publishPost(string $accessToken, string $baseUrl, Content $content): array
    {
        return $this->httpClient->request(
            method: Request::METHOD_POST,
            url: $baseUrl . '/wp-json/evdim/posts',
            options: [
                'json' => [
                    'id' => $content->id,
                    'image' => $content->image,
                    'title' => $content->title,
                    'description' => $content->description,
                    'content' => $content->content,
                    'tags' => $content->tags,
                    'categories' => $content->categories,
                    'accessToken' => $accessToken,
                ],
                'verify_peer' => false,
            ],
        )->toArray();
    }
}
