<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Gateway;

use Sofyco\Bundle\WordPressGatewayBundle\Entity\PublishPostContent;
use Sofyco\Bundle\WordPressGatewayBundle\Entity\SiteInstallation;
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

    public function createSite(SiteInstallation $siteInstallation): bool
    {
        $this->siteRepository->create(url: $siteInstallation->baseUrl);

        $response = $this->httpClient->request(
            method: Request::METHOD_POST,
            url: $siteInstallation->baseUrl . '/wp-admin/install.php',
            options: [
                'json' => $siteInstallation->configuration,
                'verify_peer' => false,
            ],
        );

        return Response::HTTP_OK === $response->getStatusCode();
    }

    public function publishPost(string $accessToken, string $baseUrl, PublishPostContent $publishPostContent): array
    {
        return $this->httpClient->request(
            method: Request::METHOD_POST,
            url: $baseUrl . '/wp-json/evdim/posts',
            options: [
                'json' => [
                    'id' => $publishPostContent->id,
                    'image' => $publishPostContent->image,
                    'title' => $publishPostContent->title,
                    'description' => $publishPostContent->description,
                    'content' => $publishPostContent->content,
                    'tags' => $publishPostContent->tags,
                    'categories' => $publishPostContent->categories,
                    'accessToken' => $accessToken,
                ],
                'verify_peer' => false,
            ],
        )->toArray();
    }
}
