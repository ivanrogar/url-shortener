<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\Locator\UrlLocatorInterface;
use App\Data\Schema\Api\Shorten\ShortenRequest;
use App\Exception\AddUrlException;
use App\Exception\Factory\CreateException;
use App\Facade\UrlFacade;
use App\Factory\Data\DataFactory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShortenController extends AbstractController
{
    use ResponseTrait;

    private UrlFacade $urlFacade;
    private UrlLocatorInterface $urlLocator;
    private DataFactory $dataFactory;
    private LoggerInterface $logger;

    public function __construct(
        UrlFacade $urlFacade,
        UrlLocatorInterface $urlLocator,
        DataFactory $dataFactory,
        LoggerInterface $logger
    ) {
        $this->urlFacade = $urlFacade;
        $this->urlLocator = $urlLocator;
        $this->dataFactory = $dataFactory;
        $this->logger = $logger;
    }

    #[Route("/shorten", name: 'shorten_post', methods: ["POST"])]
    public function postAction(Request $request): JsonResponse
    {
        try {
            /**
             * @var ShortenRequest $inputData
             */
            $inputData = $this
                ->dataFactory
                ->create(
                    DataFactory::TYPE_SHORTEN_REQUEST,
                    (array)\json_decode($request->getContent(), true)
                );
        } catch (CreateException $exception) {
            $this
                ->logger
                ->debug(
                    sprintf(
                        'Data model creation failed: %s',
                        $exception->getMessage(),
                    )
                );

            return $this
                ->messageResponse(
                    'Validation failed',
                    400,
                    [
                        $exception->getMessage()
                    ]
                );
        }

        try {
            $url = $this->urlFacade->addUrl($inputData);
        } catch (AddUrlException $exception) {
            $this->logger->alert(
                sprintf(
                    'Url facade exception: %s in %s, at #%s',
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine()
                )
            );

            return $this->messageResponse('An error has occurred', 500);
        }

        return $this->shortenResponse($url);
    }

    #[Route("/{urlAlias}", name: 'shorten_get', methods: ["GET"])]
    public function getAction(string $urlAlias): JsonResponse
    {
        $url = $this->urlLocator->locate($urlAlias);

        if ($url === null) {
            return $this->messageResponse('Url not found', 404);
        }

        return $this->redirectResponse($url);
    }
}
