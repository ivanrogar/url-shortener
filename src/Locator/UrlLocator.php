<?php

declare(strict_types=1);

namespace App\Locator;

use App\Contract\Entity\UrlInterface;
use App\Contract\Locator\UrlLocatorInterface;
use App\Contract\Repository\UrlRepositoryInterface;
use App\Exception\Database\EntityNotFoundException;

class UrlLocator implements UrlLocatorInterface
{
    private UrlRepositoryInterface $urlRepository;

    public function __construct(UrlRepositoryInterface $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    public function locate(string $urlAlias): ?UrlInterface
    {
        try {
            return $this->urlRepository->get($urlAlias);
        } catch (EntityNotFoundException) {
        }

        return null;
    }
}
