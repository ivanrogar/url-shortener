<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Contract\Client\Channel\SmsChannelInterface;
use App\Exception\Client\RequestException;
use App\Message\SmsNotification;
use App\MessageHandler\SmsNotificationHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\MessageHandler\SmsNotificationHandler
 */
class TestSmsNotificationHandler extends TestCase
{
    /**
     * @var SmsChannelInterface|MockObject
     */
    private $smsChannel;

    /**
     * @var SmsNotificationHandler
     */
    private $subject;

    protected function setUp(): void
    {
        $this->smsChannel = $this->createMock(SmsChannelInterface::class);

        $this->subject = new SmsNotificationHandler($this->smsChannel);
    }

    /**
     * @covers \App\MessageHandler\SmsNotificationHandler::__invoke
     * @throws RequestException
     */
    public function testInvoke()
    {
        $this->smsChannel->expects($this->once())->method('send');

        $subject = $this->subject;

        $subject(new SmsNotification('recipient', 'content'));
    }
}
