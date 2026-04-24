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

    public function findByDateRange(string $type, string $from, string $to): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("
            SELECT
                p.pid,
                ROUND(MIN(m.{$type}), 0) as min_{$type},
                ROUND(AVG(m.{$type}), 0) as avg_{$type},
                ROUND(MAX(m.{$type}), 0) as max_{$type},
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

    public function findRssProbesForTop5Avg(string $from, string $to): array
    {
        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("SELECT
        p.pid,
        round(m.rss/1024, 1) as val,
        UNIX_TIMESTAMP(m.collected_at) - MIN(UNIX_TIMESTAMP(m.collected_at)) OVER (PARTITION BY m.process_id) as collected_at
        FROM metric AS m
        JOIN process AS p ON p.id=m.process_id
        JOIN
        (
            SELECT DISTINCT p2.pid
                FROM process AS p2
            JOIN metric AS m2 ON p2.id=m2.process_id
            WHERE m2.collected_at BETWEEN :from AND :to
            group BY p2.id
            ORDER BY AVG(m2.rss) DESC
            LIMIT 5
        ) AS top10 ON top10.pid=p.pid
        AND m.collected_at BETWEEN :from AND :to
        ORDER BY p.pid, m.collected_at
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }

    public function findVsizeProbesForTop5Avg(string $from, string $to): array
    {

        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("SELECT
        p.pid,
        ROUND(m.vsize/1024/1024/1024, 3) as val,
        UNIX_TIMESTAMP(m.collected_at) - MIN(UNIX_TIMESTAMP(m.collected_at)) OVER (PARTITION BY p.pid)as collected_at
        FROM metric AS m
        JOIN process AS p ON p.id=m.process_id
        JOIN
        (
            SELECT DISTINCT p2.pid, AVG(m2.vsize) as avg_size
                FROM process AS p2
            JOIN metric AS m2 ON p2.id=m2.process_id
            WHERE m2.collected_at BETWEEN :from AND :to
            group BY p2.id
            ORDER BY AVG(m2.vsize) DESC
            LIMIT 5
        ) AS top10 ON top10.pid=p.pid
        WHERE m.collected_at BETWEEN :from AND :to
        ORDER BY top10.avg_size DESC, p.pid, m.collected_at
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }

    public function findStimeProbesForTop5Avg(string $from, string $to): array
    {

        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("SELECT
        p.pid,
        ROUND(m.stime/100, 3) as val,
        UNIX_TIMESTAMP(m.collected_at) - MIN(UNIX_TIMESTAMP(m.collected_at)) OVER (PARTITION BY p.pid)as collected_at
        FROM metric AS m
        JOIN process AS p ON p.id=m.process_id
        JOIN
        (
            SELECT DISTINCT p2.pid, AVG(m2.stime) as avg_stime
                FROM process AS p2
            JOIN metric AS m2 ON p2.id=m2.process_id
            WHERE m2.collected_at BETWEEN :from AND :to
            group BY p2.id
            ORDER BY AVG(m2.stime) DESC
            LIMIT 5
        ) AS top10 ON top10.pid=p.pid
        WHERE m.collected_at BETWEEN :from AND :to
        ORDER BY top10.avg_stime DESC, p.pid, m.collected_at
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }

    public function findUtimeProbesForTop5Avg(string $from, string $to): array
    {

        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("SELECT
        p.pid,
        ROUND(m.utime/100, 3) as val,
        UNIX_TIMESTAMP(m.collected_at) - MIN(UNIX_TIMESTAMP(m.collected_at)) OVER (PARTITION BY p.pid)as collected_at
        FROM metric AS m
        JOIN process AS p ON p.id=m.process_id
        JOIN
        (
            SELECT DISTINCT p2.pid, AVG(m2.utime) as avg_utime
                FROM process AS p2
            JOIN metric AS m2 ON p2.id=m2.process_id
            WHERE m2.collected_at BETWEEN :from AND :to
            group BY p2.id
            ORDER BY AVG(m2.utime) DESC
            LIMIT 5
        ) AS top10 ON top10.pid=p.pid
        WHERE m.collected_at BETWEEN :from AND :to
        ORDER BY top10.avg_utime DESC, p.pid, m.collected_at
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }

    public function findThreadsCountProbesForTop5Avg(string $from, string $to): array
    {

        $query = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("SELECT
        p.pid,
        m.num_threads as val,
        UNIX_TIMESTAMP(m.collected_at) - MIN(UNIX_TIMESTAMP(m.collected_at)) OVER (PARTITION BY p.pid) as collected_at
        FROM metric AS m
        JOIN process AS p ON p.id=m.process_id
        JOIN
        (
            SELECT DISTINCT p2.pid, AVG(m2.num_threads) as avg_num_threads
                FROM process AS p2
            JOIN metric AS m2 ON p2.id=m2.process_id
            WHERE m2.collected_at BETWEEN :from AND :to
            group BY p2.id
            ORDER BY AVG(m2.num_threads) DESC
            LIMIT 5
        ) AS top10 ON top10.pid=p.pid
        WHERE m.collected_at BETWEEN :from AND :to
        ORDER BY top10.avg_num_threads DESC, p.pid, m.collected_at
                ", [
                    'from' => $from,
                    'to' => $to
                ]);
        return $query->fetchAllAssociative();
    }
}
