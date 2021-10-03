<?php

declare(strict_types=1);

namespace App\Report;

use App\Contract\Repository\UrlRepositoryInterface;
use App\Message\SmsNotification;
use Carbon\Carbon;
use Cron\CronExpression;
use Symfony\Component\Messenger\MessageBusInterface;
use Carbon\CarbonInterface;
use DateTimeZone;

class UrlReport
{
    public const TYPE_DAILY = '0 8 * * *';
    public const TYPE_WEEKLY = '1 8 * * 1';
    public const TYPE_MONTHLY = '0 8 * * 1';

    private UrlRepositoryInterface $urlRepository;
    private MessageBusInterface $messageBus;

    private ?CarbonInterface $now = null;

    public function __construct(
        UrlRepositoryInterface $urlRepository,
        MessageBusInterface $messageBus
    ) {
        $this->urlRepository = $urlRepository;
        $this->messageBus = $messageBus;
    }

    public function processDaily()
    {
        $now = $this->getNow();

        $cron = new CronExpression(self::TYPE_DAILY);

        if ($cron->isDue($now->toDateTime())) {
            $fromDate = $now->clone();

            $fromDate->subDays(1);

            $toDate = $fromDate->clone();

            $fromDate->setTime(0, 0);

            $toDate->setTime(23, 59, 59);

            $result = $this
                ->urlRepository
                ->getUsageBetween(
                    $fromDate->toDateTime(),
                    $toDate->toDateTime()
                );

            $this
                ->messageBus
                ->dispatch(
                    new SmsNotification(
                        $_ENV['INFOBIP_SMS_RECIPIENT_NUMBER'],
                        'Daily url shortening count: ' . $result
                    )
                );
        }
    }

    public function processWeekly()
    {
        $now = $this->getNow();

        $cron = new CronExpression(self::TYPE_WEEKLY);

        if ($cron->isDue($now->toDateTime())) {
            $fromDate = $now->clone();

            $fromDate
                ->subWeek()
                ->weekday(1);

            $toDate = $fromDate
                ->clone()
                ->endOfWeek();

            $fromDate->setTime(0, 0);
            $toDate->setTime(23, 59, 59);

            $result = $this
                ->urlRepository
                ->getUsageBetween(
                    $fromDate->toDateTime(),
                    $toDate->toDateTime()
                );

            $this
                ->messageBus
                ->dispatch(
                    new SmsNotification(
                        $_ENV['INFOBIP_SMS_RECIPIENT_NUMBER'],
                        'Weekly url shortening count: ' . $result
                    )
                );
        }
    }

    public function processMonthly()
    {
        $now = $this->getNow();

        $cron = new CronExpression(self::TYPE_MONTHLY);

        if ($cron->isDue($now->toDateTime())) {
            $firstMonday = $now
                ->clone()
                ->firstOfMonth(1)
                ->setTime($now->hour, $now->minute);

            if ($firstMonday->eq($now)) {
                $fromDate = $now
                    ->clone()
                    ->subMonth()
                    ->firstOfMonth()
                    ->setTime(0, 0);

                $toDate = $now
                    ->clone()
                    ->subMonth()
                    ->lastOfMonth()
                    ->setTime(23, 59, 59);

                $result = $this
                    ->urlRepository
                    ->getUsageBetween(
                        $fromDate->toDateTime(),
                        $toDate->toDateTime()
                    );

                $this
                    ->messageBus
                    ->dispatch(
                        new SmsNotification(
                            $_ENV['INFOBIP_SMS_RECIPIENT_NUMBER'],
                            'Monthly url shortening count: ' . $result
                        )
                    );
            }
        }
    }

    private function getNow(): CarbonInterface
    {
        if ($this->now === null) {
            $this->now = Carbon::now(new DateTimeZone('Europe/Zagreb'));
        }

        return $this->now;
    }
}
