<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\Entity\UrlInterface;
use App\Factory\Data\DataFactory;
use Swaggest\JsonSchema\Structure\ClassStructure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

trait ResponseTrait
{
    public function messageResponse(string $message, int $status = 400, ?array $errors = null): JsonResponse
    {
        return $this
            ->json(
                [
                    'message' => $message,
                    'errors' => $errors,
                ],
                $status
            );
    }

    public function shortenResponse(UrlInterface $url): JsonResponse
    {
        $shortenedUrl = $this
            ->generateUrl(
                'shorten_get',
                [
                    'urlAlias' => $url->getUrlAlias()
                ],
                RouterInterface::ABSOLUTE_URL
            );

        $data = $this
            ->dataFactory
            ->create(
                DataFactory::TYPE_SHORTEN_RESPONSE,
                [
                    'shortUrl' => $shortenedUrl,
                ]
            );

        return $this
            ->json(
                $this->dataToArray($data)
            );
    }

    public function redirectResponse(UrlInterface $url): JsonResponse
    {
        $data = $this
            ->dataFactory
            ->create(
                DataFactory::TYPE_SHORTEN_REDIRECT_RESPONSE,
                [
                    'url' => $url->getRedirectUrl(),
                ]
            );

        return $this
            ->json(
                $this->dataToArray($data),
                302,
                [
                    'Location' => $data->url,
                ]
            );
    }

    private function dataToArray(ClassStructure $data): array
    {
        return \json_decode(\json_encode($data::export($data)), true);
    }
}
