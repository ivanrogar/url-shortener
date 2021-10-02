<?php

declare(strict_types=1);

namespace App\Factory\Entity;

use App\Contract\Entity\Factory\UrlFactoryInterface;
use App\Contract\Entity\UrlInterface;
use App\Entity\Url;
use PHLAK\StrGen\CharSet;
use PHLAK\StrGen\Generator as StringGenerator;

class UrlFactory implements UrlFactoryInterface
{
    private StringGenerator $stringGenerator;

    public function __construct(StringGenerator $stringGenerator)
    {
        $this->stringGenerator = $stringGenerator;
    }

    public function create(string $redirectUrl): UrlInterface
    {
        $url = new Url();

        $alias = $this
            ->stringGenerator
            ->charset(CharSet::ALPHA_NUMERIC)
            ->length(6)
            ->generate();

        $url
            ->setUrlAlias($alias)
            ->setRedirectUrl($redirectUrl);

        return $url;
    }
}
