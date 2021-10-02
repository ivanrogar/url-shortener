<?php

declare(strict_types=1);

namespace App\Client;

use App\Factory\ClientFactory;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Structure\ClassStructure;

abstract class AbstractClient
{
    protected ClientFactory $clientFactory;

    private ?ClientInterface $client = null;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    protected function getClient(): ClientInterface
    {
        if ($this->client === null) {
            $this->client = $this->clientFactory->create();
        }

        return $this->client;
    }

    /**
     * @throws GuzzleException
     * @throws InvalidValue
     */
    protected function request(string $uri, ClassStructure | array $data, string $method = 'POST'): ResponseInterface
    {
        if ($data instanceof ClassStructure) {
            $data = \json_decode(\json_encode($data::export($data)), true);
        }

        $client = $this->getClient();

        $headers = [
            'Authorization' => 'App ' . $_ENV['INFOBIP_API_KEY'],
        ];

        return $client->request(
            $method,
            $uri,
            [
                'json' => $data,
                'headers' => $headers,
            ]
        );
    }
}
