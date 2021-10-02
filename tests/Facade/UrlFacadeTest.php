<?php

declare(strict_types=1);

namespace App\Tests\Facade;

use App\Contract\Entity\Factory\UrlFactoryInterface;
use App\Contract\Repository\UrlRepositoryInterface;
use App\Data\Schema\Api\Shorten\ShortenRequest;
use App\Exception\AddUrlException;
use App\Facade\UrlFacade;
use App\Factory\Entity\UrlFactory;
use App\Message\SmsNotification;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Swaggest\JsonSchema\Exception;
use Swaggest\JsonSchema\InvalidValue;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use PHLAK\StrGen\Generator as StringGenerator;

/**
 * @covers \App\Facade\UrlFacade
 */
class UrlFacadeTest extends TestCase
{
    /**
     * @var UrlFactoryInterface|MockObject
     */
    protected $urlFactory;

    /**
     * @var UrlRepositoryInterface|MockObject
     */
    protected $urlRepository;

    /**
     * @var MessageBusInterface|MockObject
     */
    protected $messageBus;

    /**
     * @var UrlFacade
     */
    protected $subject;

    public function setUp(): void
    {
        $this->urlFactory = new UrlFactory(new StringGenerator());

        $this->urlRepository = $this->createMock(UrlRepositoryInterface::class);

        $this->messageBus = $this->createMock(MessageBusInterface::class);

        $this->subject = new UrlFacade(
            $this->urlFactory,
            $this->urlRepository,
            $this->messageBus
        );
    }

    /**
     * @throws Exception
     * @throws InvalidValue
     * @throws AddUrlException
     */
    public function testAddUrl()
    {
        $rawRequest = \file_get_contents(
            dirname(__FILE__) . '/../files/api/shorten/shorten.request.json'
        );

        $request = ShortenRequest::import(
            \json_decode($rawRequest)
        );

        $this->urlRepository->expects($this->once())->method('save');

        $this->messageBus->expects($this->never())->method('dispatch');

        $this->subject->addUrl($request);
    }

    /**
     * @throws Exception
     * @throws InvalidValue
     * @throws AddUrlException
     */
    public function testAddInfoBipUrl()
    {
        $rawRequest = \file_get_contents(
            dirname(__FILE__) . '/../files/api/shorten/shorten.infobip.request.json'
        );

        $request = ShortenRequest::import(
            \json_decode($rawRequest)
        );

        $this->urlRepository->expects($this->once())->method('save');

        $this
            ->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->withConsecutive(
                [
                    self::isInstanceOf(SmsNotification::class)
                ]
            )
            ->willReturnCallback(
                function () {
                    return new Envelope(new SmsNotification('recipient', 'content'));
                }
            );

        $this->subject->addUrl($request);
    }
}
