<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'handleException'
        ];
    }

    public function handleException(ExceptionEvent $event)
    {
        $request = $event->getRequest();

        if ($request->headers->get('content-type') === 'application/json') {
            $exception = $event->getThrowable();

            if ($exception instanceof BadRequestHttpException) {
                $statusCode = 400;
            } else {
                $statusCode = $event->getResponse()?->getStatusCode() ?? 500;
            }

            $event
                ->setResponse(
                    new JsonResponse(
                        [
                            'message' => $exception->getMessage(),
                        ],
                        $statusCode
                    )
                );
        }
    }
}
