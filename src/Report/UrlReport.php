<?php

declare(strict_types=1);

namespace App\Report;

use App\Contract\Repository\UrlRepositoryInterface;
use App\Message\SmsNotification;
use Cron\CronExpression;
use Symfony\Component\Messenger\MessageBusInterface;
use DateTime;
use DateTimeZone;

class UrlReport
{
    public const TYPE_DAILY = '0 8 * * *';
    public const TYPE_WEEKLY = '1 8 * * 1';
    public const TYPE_MONTHLY = '0 8 * * 1';

    private UrlRepositoryInterface $urlRepository;
    private MessageBusInterface $messageBus;

    public function __construct(
        UrlRepositoryInterface $urlRepository,
        MessageBusInterface $messageBus
    ) {
        $this->urlRepository = $urlRepository;
        $this->messageBus = $messageBus;
    }

    public function processAll(?DateTime $now = null)
    {
        if ($now === null) {
            $now = new DateTime(date('Y-m-d H:m:s'), new DateTimeZone('Europe/Zagreb'));
        }

        $this->processDaily($now);

        $this->processWeekly($now);

        $this->processMonthly($now);
    }

    public function processDaily(DateTime $now)
    {
        $cron = new CronExpression(self::TYPE_DAILY);

        if ($cron->isDue($now)) {
            $fromDate = clone $now;

            $fromDate->modify('-1 days');

            $toDate = clone $fromDate;

            $fromDate->setTime(0, 0);
            $toDate->setTime(23, 59, 59);

            $result = $this->urlRepository->getUsageBetween($fromDate, $toDate);

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

    public function processWeekly(DateTime $now)
    {
        $cron = new CronExpression(self::TYPE_WEEKLY);

        if ($cron->isDue($now)) {
            $fromDate = clone $now;

            $fromDate->modify('-7 days');

            $toDate = clone $now;

            $toDate->modify('-1 days');

            $fromDate->setTime(0, 0);
            $toDate->setTime(23, 59, 59);

            $result = $this->urlRepository->getUsageBetween($fromDate, $toDate);

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

    public function processMonthly(DateTime $now)
    {
        $cron = new CronExpression(self::TYPE_MONTHLY);

        if ($cron->isDue($now)) {
            $firstMonday = DateTime::createFromFormat(
                'Y-m-d',
                date('Y-m-d', strtotime('first monday ' . $now->format('Y-m')))
            );

            if ($now->format('Y-m-d') === $firstMonday->format('Y-m-d')) {
                $fromDate = DateTime::createFromFormat(
                    'Y-m-d',
                    date('Y-m-d', strtotime('first day of last month'))
                );

                $toDate = DateTime::createFromFormat(
                    'Y-m-d',
                    date('Y-m-d', strtotime('last day of last month'))
                );

                $fromDate->setTime(0, 0);
                $toDate->setTime(23, 59, 59);

                $result = $this->urlRepository->getUsageBetween($fromDate, $toDate);

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
}
