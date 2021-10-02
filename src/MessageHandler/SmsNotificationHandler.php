<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Contract\Client\Channel\SmsChannelInterface;
use App\Data\Schema\Client\Sms\Request\MessagesItems;
use App\Data\Schema\Client\Sms\Request\MessagesItemsDestinationsItems;
use App\Data\Schema\Client\Sms\Request\SmsMessageRequest;
use App\Exception\Client\RequestException;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    private SmsChannelInterface $smsChannel;

    public function __construct(SmsChannelInterface $smsChannel)
    {
        $this->smsChannel = $smsChannel;
    }

    /**
     * @throws RequestException
     */
    public function __invoke(SmsNotification $notification)
    {
        $destination = new MessagesItemsDestinationsItems();

        $destination->setTo($notification->getRecipient());

        $message = new MessagesItems();

        $message
            ->setFrom('Info')
            ->setText($notification->getContent())
            ->setDestinations(
                [
                    $destination
                ]
            );

        $request = new SmsMessageRequest();

        $request->setMessages(
            [
                $message
            ]
        );

        $this->smsChannel->send($request);
    }
}
