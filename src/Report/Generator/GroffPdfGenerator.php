<?php

namespace App\Report\Generator;

class GroffPdfGenerator
{
    public function __construct(private readonly string $projectDir)
    {}

    public function render(array $data): string
    {
        ob_start();
        include $this->projectDir . '/src/Report/Template/Report.mm.php';
        return ob_get_clean();
    }
}
