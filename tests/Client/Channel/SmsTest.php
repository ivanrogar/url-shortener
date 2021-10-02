<?php

declare(strict_types=1);

namespace App\Tests\Client\Channel;

use App\Client\Channel\Sms;
use App\Data\Schema\Client\Sms\Request\SmsMessageRequest;
use App\Exception\Client\RequestException;
use App\Factory\ClientFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\InvalidValue;

/**
 * @covers \App\Client\Channel\Sms
 */
class SmsTest extends TestCase
{
    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientFactory|MockObject
     */
    protected $clientFactory;

    /**
     * @var Sms
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        $this->client = new Client(['handler' => HandlerStack::create($this->mockHandler)]);

        $this->clientFactory = $this->createMock(ClientFactory::class);

        $this->clientFactory->method('create')->willReturn($this->client);

        $this->subject = new Sms($this->clientFactory);
    }

    /**
     * @throws Exception
     * @throws InvalidValue
     * @throws RequestException
     */
    public function testSend()
    {
        $rawRequest = \file_get_contents(
            dirname(__FILE__) . '/../../files/client/channel/message.request.json'
        );

        $rawResponse = \file_get_contents(
            dirname(__FILE__) . '/../../files/client/channel/message.response.json'
        );

        $this
            ->mockHandler
            ->append(
                new Response(200, [], $rawResponse)
            );

        $request = SmsMessageRequest::import(
            \json_decode(
                $rawRequest
            )
        );

        $response = $this->subject->send($request);

        $this->assertSame(
            \json_decode(\json_encode($response::export($response)), true),
            \json_decode($rawResponse, true)
        );
    }
}
