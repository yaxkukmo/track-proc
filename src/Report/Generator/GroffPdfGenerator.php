<?php

namespace App\Report\Generator;

class GroffPdfGenerator
{
    public function generate(
        array $cpuData,
        array $memData,
        array $vszData,
        array $rssData
    ): string
    {
        $this->createTableFile($cpuData, 'cpu');
        $this->createTableFile($memData, 'mem');
        $this->createTableFile($vszData, 'vsz');
        $this->createTableFile($rssData, 'rss');
        $this->createMainReportFile();
        shell_exec('groff -Tpdf -s -t -mm /tmp/report.mm > /tmp/report.pdf');
        return '/tmp/report.mm';
    }

    private function createMainReportFile(): bool
    {
        $code = ".fp 1 H
.fp 2  HI
.fp 3 HB
.nr HF 3
.fp 4 HBI
.PH \"\"
.PF \"'System resources report'- % -'2026-01-01 00:00 - 20260-04-15 23.59'\"
.SK
.ce
.B \"CPU usage\"
.so /tmp/cpu.tbl
.SK
.ce
.B \"Memory usage\"
.so /tmp/mem.tbl
.SK
.ce
.B \"Virtual memory usage\"
.so /tmp/vsz.tbl
.SK
.ce
.B \"Resident memory usage\"
.so /tmp/rss.tbl";

        return file_put_contents('/tmp/report.mm', $code) === true;
    }

    private function createTableFile(array $data, string $type): bool
    {

        $filteredData = array_map(function($rowData) use ($type) {
            return '\&\x\'4p\''.$this->stripLongString($rowData['command'])
                .';'
                .$rowData['user']
                .';'
                .$rowData['avg_' . $type]
                .';'
                .$rowData['min_' . $type]
                .';'
                .$rowData['max_' . $type];
        }, $data);
        $code = ".TS H
linesize(1) expand center tab(;);
lb1| lb1| lb1| lb1| lb
l1| l2| N1| N1| N.
\\f3Command\\f1;\\f3User\\f1;\\f3Avg " . $type . "\\f1;\\f3Min " . $type . "\\f1;\\f3Max " . $type . "\\f1
_
.TH
;;;;
" . implode("\n", $filteredData) . "
_
.TE
";
        return file_put_contents('/tmp/'. $type .'.tbl', $code) === true;
    }

    private function stripLongString(string $data): string
    {
        return strlen($data) > 42 ? substr($data, 0, 42) . '...' : $data;
    }
}
