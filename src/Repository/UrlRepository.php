<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\Entity\UrlInterface;
use App\Contract\Repository\UrlRepositoryInterface;
use App\Entity\Url;
use App\Exception\Database\CannotDeleteException;
use App\Exception\Database\CannotSaveException;
use App\Exception\Database\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use DateTime;

class UrlRepository extends ServiceEntityRepository implements UrlRepositoryInterface
{
    private LoggerInterface $logger;

    public function __construct(
        ManagerRegistry $registry,
        LoggerInterface $logger
    ) {
        parent::__construct($registry, Url::class);

        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function get(string $urlAlias): UrlInterface
    {
        /**
         * @var UrlInterface[] $results
         */
        $results = $this
            ->createQueryBuilder('q')
            ->where('q.urlAlias = :urlAlias')
            ->setParameter('urlAlias', $urlAlias)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (is_iterable($results) && count($results)) {
            return current($results);
        }

        throw new EntityNotFoundException('Url alias not found');
    }

    public function getUsageBetween(DateTime $fromDate, DateTime $toDate): int
    {
        try {
            return (int)$this
                ->createQueryBuilder('q')
                ->select('COUNT(q.id)')
                ->where('q.createdAt BETWEEN :fromDate AND :toDate')
                ->setParameter('fromDate', $fromDate->format('Y-m-d H:i:s'))
                ->setParameter('toDate', $toDate->format('Y-m-d H:i:s'))
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $exception) {
            $this
                ->logger
                ->critical(
                    sprintf(
                        'Url repository usage between exception: %s',
                        $exception->getMessage()
                    )
                );
        }

        return 0;
    }

    public function save(UrlInterface $url): void
    {
        $ema = $this->getEntityManager();

        try {
            $ema->persist($url);
            $ema->flush();
        } catch (ORMException $exception) {
            throw new CannotSaveException(
                'Cannot save url',
                0,
                $exception
            );
        }
    }

    public function delete(UrlInterface $url): void
    {
        $ema = $this->getEntityManager();

        try {
            $ema->remove($url);
            $ema->flush();
        } catch (ORMException $exception) {
            throw new CannotDeleteException(
                'Cannot delete url',
                0,
                $exception
            );
        }
    }
}
