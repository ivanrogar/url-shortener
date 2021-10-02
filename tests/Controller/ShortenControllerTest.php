<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\UnicodeString;

/**
 * @covers \App\Controller\ShortenController
 */
class ShortenControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createClient();
    }

    public function testPostAction()
    {
        $response = $this->makeUrl('https://www.google.com');

        $this->assertSame($response->getStatusCode(), 200);

        $data = \json_decode($response->getContent(), true);

        $string = new UnicodeString($data['shortUrl']);

        $this->assertTrue($string->startsWith('http://localhost/'));
    }

    public function testGetAction()
    {
        $client = $this->client;

        $response = $this->makeUrl('https://www.google.com');

        $this->assertSame($response->getStatusCode(), 200);

        $data = \json_decode($response->getContent(), true);

        $parts = explode('/', $data['shortUrl']);

        $shortUrl = end($parts);

        $client->request('GET', '/' . $shortUrl);

        $redirectedResponse = $client->getResponse();

        $this->assertSame($redirectedResponse->getStatusCode(), 302);

        $this->assertTrue($redirectedResponse->isRedirection());

        $this->assertTrue(
            $redirectedResponse->isRedirect('https://www.google.com')
        );
    }

    protected function makeUrl(string $url): Response
    {
        $this->client->request(
            'POST',
            '/shorten',
            [],
            [],
            [],
            \json_encode(
                [
                    'url' => $url
                ]
            )
        );

        return $this->client->getResponse();
    }
}
