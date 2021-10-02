<?php

declare(strict_types=1);

namespace App\Contract\Entity;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

interface UrlInterface
{
    public function getId(): ?UuidInterface;

    public function setId(UuidInterface $uuid): self;

    public function getUrlAlias(): ?string;

    public function setUrlAlias(string $alias): self;

    public function getRedirectUrl(): ?string;

    public function setRedirectUrl(string $url): self;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(DateTimeInterface $dateTime): self;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(DateTimeInterface $dateTime): self;
}
