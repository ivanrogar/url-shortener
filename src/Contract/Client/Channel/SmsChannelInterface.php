<?php

declare(strict_types=1);

namespace App\Contract\Client\Channel;

use App\Contract\Client\ClientInterface;
use App\Data\Schema\Client\Sms\Request\SmsMessageRequest;
use App\Data\Schema\Client\Sms\Response\SmsMessageResponse;
use App\Exception\Client\RequestException;

interface SmsChannelInterface extends ClientInterface
{
    /**
     * @throws RequestException
     */
    public function send(SmsMessageRequest $message): SmsMessageResponse;
}
