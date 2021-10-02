<?php

declare(strict_types=1);

namespace App\Factory;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ClientFactory
{
    public function create(array $options = []): ClientInterface
    {
        $options = array_replace(['base_uri' => $_ENV['INFOBIP_API_BASE_URL']], $options);

        return new Client($options);
    }
}
