<?php

declare(strict_types=1);

namespace App\Contract\Entity\Factory;

use App\Contract\Entity\UrlInterface;

interface UrlFactoryInterface
{
    public function create(
        string $redirectUrl
    ): UrlInterface;
}
