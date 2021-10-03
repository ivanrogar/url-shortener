<?php

declare(strict_types=1);

namespace App\Tests\Report;

use App\Contract\Repository\UrlRepositoryInterface;
use App\Report\UrlReport;
use App\Message\SmsNotification;
use Carbon\Carbon;
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

    protected function tearDown(): void
    {
        Carbon::setTestNow();
    }

    public function testProcessDaily()
    {
        $now = Carbon::now(new DateTimeZone('Europe/Zagreb'));

        $now->setTime(8, 0);

        Carbon::setTestNow($now);

        $expectedFrom = $now->clone();
        $expectedFrom->subDays(1);
        $expectedFrom->setTime(0, 0);

        $expectedTo = $expectedFrom->clone();
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

        $this->subject->processDaily();
    }

    public function testProcessWeekly()
    {
        $carbon = Carbon::now(new DateTimeZone('Europe/Zagreb'));

        $carbon
            ->addWeek()
            ->weekday(1)
            ->setTime(8, 1);

        Carbon::setTestNow($carbon);

        $expectedFrom = $carbon->clone();

        $expectedFrom
            ->subWeek()
            ->weekday(1)
            ->setTime(0, 0);

        $expectedTo = $expectedFrom
            ->clone()
            ->endOfWeek()
            ->setTime(23, 59, 59);

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

        $this->subject->processWeekly();
    }

    public function testProcessMonthly()
    {
        $carbon = Carbon::now(new DateTimeZone('Europe/Zagreb'));

        $carbon
            ->firstOfMonth(1)
            ->setTime(8, 0);

        Carbon::setTestNow($carbon);

        $expectedFrom = $carbon->clone();

        $expectedFrom
            ->subMonth()
            ->firstOfMonth()
            ->setTime(0, 0);

        $expectedTo = $carbon->clone();

        $expectedTo
            ->subMonth()
            ->lastOfMonth()
            ->setTime(23, 59, 59);

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

        $this->subject->processMonthly();
    }
}
