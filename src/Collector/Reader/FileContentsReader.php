<?php

namespace App\Collector\Reader;

class FileContentsReader
{
    public function __invoke(array $pathList): array
    {
        $rawData = array_map(fn($paths): array =>
            array_map(
                fn($path): ?string => $this->readFile($path),
                $paths
            ),
            $pathList
        );
        return $rawData;
    }

    private function readFile(string $path): ?string
    {
        try {
            $contents = file_get_contents($path);
        } catch (\Exception $e) {
            $contents = null;
        }
        return $contents;
    }
}
