<?php

declare(strict_types=1);

namespace App\Facade;

use App\Contract\Entity\Factory\UrlFactoryInterface;
use App\Contract\Entity\UrlInterface;
use App\Contract\Repository\UrlRepositoryInterface;
use App\Data\Schema\Api\Shorten\ShortenRequest;
use App\Exception\AddUrlException;
use App\Exception\Database\CannotSaveException;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\MessageBusInterface;

class UrlFacade
{
    private UrlFactoryInterface $urlFactory;
    private UrlRepositoryInterface $urlRepository;
    private MessageBusInterface $messageBus;

    public function __construct(
        UrlFactoryInterface $urlFactory,
        UrlRepositoryInterface $urlRepository,
        MessageBusInterface $messageBus
    ) {
        $this->urlFactory = $urlFactory;
        $this->urlRepository = $urlRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @throws AddUrlException
     */
    public function addUrl(ShortenRequest $inputData): UrlInterface
    {
        $url = $this->urlFactory->create($inputData->url);

        try {
            $this->urlRepository->save($url);
        } catch (CannotSaveException $exception) {
            throw new AddUrlException(
                sprintf(
                    'Add url failed: %s',
                    $exception->getMessage()
                ),
                0,
                $exception
            );
        }

        $redirectUrl = $url->getRedirectUrl();

        $host = parse_url($redirectUrl, PHP_URL_HOST);

        if (
            in_array(
                $host,
                [
                'infobip.com',
                'www.infobip.com',
                ]
            )
        ) {
            $this->messageBus->dispatch(
                new SmsNotification(
                    $_ENV['INFOBIP_SMS_RECIPIENT_NUMBER'],
                    'Received request for shortening URL: ' . $redirectUrl,
                )
            );
        }

        return $url;
    }
}
