<?php

declare(strict_types=1);

namespace App\Tests\Report;

use App\Contract\Repository\UrlRepositoryInterface;
use App\Report\UrlReport;
use App\Message\SmsNotification;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use DateTime;
use DateTimeZone;

/**
 * @covers \App\Report\UrlReport
 */
class UrlReportTest extends TestCase
{
    /**
     * @var UrlRepositoryInterface|MockObject
     */
    protected $urlRepository;

    /**
     * @var MessageBusInterface|MockObject
     */
    protected $messageBus;

    /**
     * @var UrlReport
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->urlRepository = $this->createMock(UrlRepositoryInterface::class);

        $this->messageBus = $this->createMock(MessageBusInterface::class);

        $this->subject = new UrlReport(
            $this->urlRepository,
            $this->messageBus
        );
    }

    public function testProcessDaily()
    {
        $now = new DateTime(date('Y-m-d H:i:s', strtotime('2021-10-02 08:00:00')));

        $expectedFrom = clone $now;
        $expectedFrom->modify('-1 days');
        $expectedFrom->setTime(0, 0);

        $expectedTo = clone $expectedFrom;
        $expectedTo->setTime(23, 59, 59);

        $this
            ->urlRepository
            ->method('getUsageBetween')
            ->willReturnCallback(
                function (DateTime $from, DateTime $to) use ($expectedFrom, $expectedTo) {
                    self::assertSame(
                        $from->format('Y-m-d H:i:s'),
                        $expectedFrom->format('Y-m-d H:i:s')
                    );

                    self::assertSame(
                        $to->format('Y-m-d H:i:s'),
                        $expectedTo->format('Y-m-d H:i:s')
                    );

                    return 0;
                }
            );

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

        $this->subject->processDaily($now);
    }

    public function testProcessWeekly()
    {
        $now = DateTime::createFromFormat(
            'Y-m-d',
            date('Y-m-d', strtotime('next monday'))
        );

        $now->setTime(8, 1);

        $expectedFrom = clone $now;
        $expectedFrom->modify('-7 days');
        $expectedFrom->setTime(0, 0);

        $expectedTo = clone $now;
        $expectedTo->modify('-1 days');
        $expectedTo->setTime(23, 59, 59);

        $this
            ->urlRepository
            ->method('getUsageBetween')
            ->willReturnCallback(
                function (DateTime $from, DateTime $to) use ($expectedFrom, $expectedTo) {
                    self::assertSame(
                        $from->format('Y-m-d H:i:s'),
                        $expectedFrom->format('Y-m-d H:i:s')
                    );

                    self::assertSame(
                        $to->format('Y-m-d H:i:s'),
                        $expectedTo->format('Y-m-d H:i:s')
                    );

                    return 0;
                }
            );


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

        $this->subject->processWeekly($now);
    }

    public function testProcessMonthly()
    {
        $now = new DateTime('now', new DateTimeZone('Europe/Zagreb'));

        $now = DateTime::createFromFormat(
            'Y-m-d',
            date('Y-m-d', strtotime('first monday ' . $now->format('Y-m')))
        );

        $now->setTime(8, 0);

        $expectedFrom = DateTime::createFromFormat(
            'Y-m-d',
            date('Y-m-d', strtotime('first day of last month'))
        );

        $expectedFrom->setTime(0, 0);

        $expectedTo = DateTime::createFromFormat(
            'Y-m-d',
            date('Y-m-d', strtotime('last day of last month'))
        );

        $expectedTo->setTime(23, 59, 59);

        $this
            ->urlRepository
            ->method('getUsageBetween')
            ->willReturnCallback(
                function (DateTime $from, DateTime $to) use ($expectedFrom, $expectedTo) {
                    self::assertSame(
                        $from->format('Y-m-d H:i:s'),
                        $expectedFrom->format('Y-m-d H:i:s')
                    );

                    self::assertSame(
                        $to->format('Y-m-d H:i:s'),
                        $expectedTo->format('Y-m-d H:i:s')
                    );

                    return 0;
                }
            );


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

        $this->subject->processMonthly($now);
    }
}
