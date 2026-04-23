<?php

namespace App\Report\Generator;

use App\Repository\MetricRepository;

class GroffPdfGenerator
{
    private array $data = [];

    public function __construct(private readonly string $projectDir)
    {

    }

    public function render(array $data): string
    {
        ob_start();
        include $this->projectDir . '/src/Report/Template/Report.mm.php';
        return ob_get_clean();
    }
}
