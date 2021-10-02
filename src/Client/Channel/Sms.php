<?php

declare(strict_types=1);

namespace App\Client\Channel;

use App\Client\AbstractClient;
use App\Contract\Client\Channel\SmsChannelInterface;
use App\Data\Schema\Client\Sms\Request\SmsMessageRequest;
use App\Data\Schema\Client\Sms\Response\SmsMessageResponse;
use App\Exception\Client\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Swaggest\JsonSchema\Exception;

class Sms extends AbstractClient implements SmsChannelInterface
{
    public function send(SmsMessageRequest $message): SmsMessageResponse
    {
        try {
            $response = $this->request(
                'sms/2/text/advanced',
                $message
            );

            return SmsMessageResponse::import(\json_decode($response->getBody()->getContents()));
        } catch (GuzzleException | Exception $exception) {
            throw new RequestException($exception->getMessage(), 0, $exception);
        }
    }
}
