<?php

namespace App\Repository;

use App\Entity\TickerData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

class TickerDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TickerData::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function isPeriod(int $period):? bool
    {
        $qb = $this->createQueryBuilder('ticker_data');

        $query = $qb
            ->select('COUNT(ticker_data.id) AS c')
            ->getQuery()
        ;

        return $query->getSingleScalarResult() >= $period;
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getSmaByPeriod(int $period):? float
    {
        $sql = 'SELECT AVG(sub.last_price) AS sma FROM (
            SELECT id, last_price FROM ticker_data ORDER BY id DESC LIMIT ?
        ) sub';

        $res = $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                $sql,
                [$period],
                [ParameterType::INTEGER]
            );

        return (float) $res->fetchFirstColumn()[0];
    }
}
