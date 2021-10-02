<?php

declare(strict_types=1);

namespace App\Tests\Locator;

use App\Contract\Repository\UrlRepositoryInterface;
use App\Entity\Url;
use App\Locator\UrlLocator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Locator\UrlLocator
 */
class UrlLocatorTest extends TestCase
{
    /**
     * @var UrlRepositoryInterface|MockObject
     */
    private $urlRepository;

    /**
     * @var UrlLocator
     */
    private $subject;

    protected function setUp(): void
    {
        $this->urlRepository = $this->createMock(UrlRepositoryInterface::class);

        $this->subject = new UrlLocator(
            $this->urlRepository
        );
    }

    public function testLocate()
    {
        $alias = 'some-url-alias';
        $redirectUrl = 'https://www.google.com';

        $this->urlRepository->method('get')->willReturnCallback(
            function () use ($alias, $redirectUrl) {
                $url = new Url();

                $url
                    ->setUrlAlias($alias)
                    ->setRedirectUrl($redirectUrl);

                return $url;
            }
        );

        $url = $this->subject->locate('some-url-alias');

        $this->assertSame($alias, $url->getUrlAlias());

        $this->assertSame($redirectUrl, $url->getRedirectUrl());
    }
}
