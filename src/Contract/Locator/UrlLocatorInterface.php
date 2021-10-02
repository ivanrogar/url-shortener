<?php

declare(strict_types=1);

namespace App\Contract\Locator;

use App\Contract\Entity\UrlInterface;

interface UrlLocatorInterface
{
    public function locate(string $urlAlias): ?UrlInterface;
}
