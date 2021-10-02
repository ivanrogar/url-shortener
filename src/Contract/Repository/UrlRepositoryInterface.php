<?php

declare(strict_types=1);

namespace App\Contract\Repository;

use App\Contract\Entity\UrlInterface;
use App\Exception\Database\CannotDeleteException;
use App\Exception\Database\CannotSaveException;
use App\Exception\Database\EntityNotFoundException;
use DateTime;

interface UrlRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function get(string $urlAlias): UrlInterface;

    public function getUsageBetween(DateTime $fromDate, DateTime $toDate): int;

    /**
     * @throws CannotSaveException
     */
    public function save(UrlInterface $url): void;

    /**
     * @throws CannotDeleteException
     */
    public function delete(UrlInterface $url): void;
}
