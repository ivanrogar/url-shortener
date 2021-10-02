<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contract\Entity\UrlInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Id\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Gedmo\Mapping\Annotation\Timestampable;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: "App\Repository\UrlRepository")]
#[ORM\Table(name: "url")]
/**
 * @SuppressWarnings(ShortVariable)
 */
class Url implements UrlInterface
{
    /**
     * @var UuidInterface
     */
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\Column(type: "string", unique: true)]
    private $urlAlias;

    #[ORM\Column(type: "string", length: 2048)]
    private $redirectUrl;

    #[ORM\Column(type: "datetime")]
    /**
     * @Timestampable(on="create")
     */
    private $createdAt;

    #[ORM\Column(type: "datetime")]
    /**
     * @Timestampable(on="update")
     */
    private $updatedAt;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $uuid): self
    {
        $this->id = $uuid;
        return $this;
    }

    public function getUrlAlias(): ?string
    {
        return $this->urlAlias;
    }

    public function setUrlAlias(string $alias): self
    {
        $this->urlAlias = $alias;
        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(string $url): self
    {
        $this->redirectUrl = $url;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $dateTime): self
    {
        $this->createdAt = $dateTime;
        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $dateTime): self
    {
        $this->updatedAt = $dateTime;
        return $this;
    }
}
