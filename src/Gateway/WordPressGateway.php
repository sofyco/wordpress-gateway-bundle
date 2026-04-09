<?php declare(strict_types=1);

namespace Sofyco\Bundle\WordPressGatewayBundle\Gateway;

use Sofyco\Bundle\WordPressGatewayBundle\Entity\ContentInterface;
use Sofyco\Bundle\WordPressGatewayBundle\Repository\DatabaseRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class WordPressGateway
{
    public function __construct(private HttpClientInterface $httpClient, private DatabaseRepositoryInterface $databaseRepository)
    {
    }

    public function isSiteExists(string $baseUrl): bool
    {
        return $this->databaseRepository->isExists(
            database: $this->getDatabaseByUrl(url: $baseUrl),
        );
    }

    public function createSite(string $baseUrl, mixed $configuration): bool
    {
        $this->databaseRepository->create(
            database: $this->getDatabaseByUrl(url: $baseUrl),
        );

        $response = $this->httpClient->request(
            method: Request::METHOD_POST,
            url: $baseUrl . '/wp-admin/install.php',
            options: [
                'json' => $configuration,
                'verify_peer' => false,
            ],
        );

        return Response::HTTP_OK === $response->getStatusCode();
    }

    public function publishPost(string $accessToken, string $baseUrl, ContentInterface $content): array
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

    private function getDatabaseByUrl(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST);

        if (false === is_string($domain)) {
            throw new \InvalidArgumentException('Invalid domain');
        }

        return str_replace('.', '_', $domain);
    }
}
