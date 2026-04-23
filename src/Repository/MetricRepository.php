<?php

namespace App\Repository;

use App\Entity\Metric;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Metric>
 */
class MetricRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metric::class);
    }

    public function findByDateRangeInGb(string $type, string $from, string $to): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("
            SELECT
                p.pid,
                ROUND(MIN(m.{$type})/1024/1024/1024, 1) as min_{$type},
                ROUND(AVG(m.{$type})/1024/1024/1024, 1) as avg_{$type},
                ROUND(MAX(m.{$type})/1024/1024/1024, 1) as max_{$type},
                p.name
            FROM metric AS m
            JOIN process AS p on p.id=m.process_id
            WHERE m.collected_at BETWEEN :from AND :to
            AND m.{$type} > 0
            GROUP BY p.pid
            ORDER BY m.{$type} DESC
            LIMIT 10
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }

    public function findByDateRangeInMb(string $type, string $from, string $to): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("
            SELECT
                p.pid,
                ROUND(MIN(m.{$type})*4096/1024/1024, 1) as min_{$type},
                ROUND(AVG(m.{$type})*4096/1024/1024, 1) as avg_{$type},
                ROUND(MAX(m.{$type})*4096/1024/1024, 1) as max_{$type},
                p.name
            FROM metric AS m
            JOIN process AS p on p.id=m.process_id
            WHERE m.collected_at BETWEEN :from AND :to
            AND m.{$type} > 0
            GROUP BY p.pid
            ORDER BY m.{$type} DESC
            LIMIT 10
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }

    public function findByDateRangeInSeconds(string $type, string $from, string $to): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("
            SELECT
                p.pid,
                ROUND(MIN(m.{$type})/100, 1) as min_{$type},
                ROUND(AVG(m.{$type})/100, 1) as avg_{$type},
                ROUND(MAX(m.{$type})/100, 1) as max_{$type},
                p.name
            FROM metric AS m
            JOIN process AS p on p.id=m.process_id
            WHERE m.collected_at BETWEEN :from AND :to
            AND m.{$type} > 0
            GROUP BY p.pid
            ORDER BY m.{$type} DESC
            LIMIT 10
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }
}
