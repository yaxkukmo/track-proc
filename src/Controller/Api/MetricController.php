<?php

namespace App\Controller\Api;

use App\Repository\MetricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class MetricController extends AbstractController
{
    public function __construct(private readonly MetricRepository $repo) {}

    #[Route('/metrics/summary', methods: ['GET'])]
    public function summary(Request $request): JsonResponse
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $from = '2026-04-21 00:00:00';
        $to = '2026-04-23 00:00:00';
        $rssData = $this->createDataset($this->repo->findRssProbesForTop5Avg($from, $to));
        return $this->json($rssData);
    }

    private function createDataset(array $rowData): array
    {
        $dataset = [];
        foreach($rowData as $line) {
            $dataset[$line['pid']][] = [$line['val'], $line['collected_at']];
        }
        return $dataset;
    }
}

