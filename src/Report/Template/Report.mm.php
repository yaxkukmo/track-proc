.nr F 3
.PH ""
.PF "'System resources report'- % -'2026-01-01 00:00 - 20260-04-15 23.59'"
.SK
.ce
.B "Virtual memory usage"
.TS H
linesize(1) expand center tab(;);
lb1| lb1| lb1| lb1| lb
l1| l2| N1| N1| N.
\f3Command\f1;\f3Pid\f1;\f3Avg vsize[Gb]\f1;\f3Min vsize[Gb]\f1;\f3Max vize[Gb]\f1
_
.TH
;;;;
<?php foreach($data['vsize'] as $l): ?>
\&\x'4p'<?=$l['name'] ?>;<?=$l['pid'] ?>;<?=$l['avg_vsize'] ?>;<?=$l['min_vsize'] ?>;<?=$l['max_vsize'] ?>

<?php endforeach; ?>
.TE
.SK
.ce
.B "Resident memory usage"
.TS H
linesize(1) expand center tab(;);
lb1| lb1| lb1| lb1| lb
l1| l2| N1| N1| N.
\f3Command\f1;\f3Pid\f1;\f3Avg rss[Mb]\f1;\f3Min rss[Mb]\f1;\f3Max rss[Mb]\f1
_
.TH
;;;;
<?php foreach($data['rss'] as $l): ?>
\&\x'4p'<?=$l['name'] ?>;<?=$l['pid'] ?>;<?=$l['avg_rss'] ?>;<?=$l['min_rss'] ?>;<?=$l['max_rss'] ?>

<?php endforeach; ?>
.TE

.SK
.ce
.B "Kernel space CPU time"
.TS H
linesize(1) expand center tab(;);
lb1| lb1| lb1| lb1| lb
l1| l2| N1| N1| N.
\f3Command\f1;\f3Pid\f1;\f3Avg stime[s]\f1;\f3Min stime[s]\f1;\f3Max stime[s]\f1
_
.TH
;;;;
<?php foreach($data['stime'] as $l): ?>
\&\x'4p'<?=$l['name'] ?>;<?=$l['pid'] ?>;<?=$l['avg_stime'] ?>;<?=$l['min_stime'] ?>;<?=$l['max_stime'] ?>

<?php endforeach; ?>
.TE

.SK
.ce
.B "User space CPU time"
.TS H
linesize(1) expand center tab(;);
lb1| lb1| lb1| lb1| lb
l1| l2| N1| N1| N.
\f3Command\f1;\f3Pid\f1;\f3Avg utime[s]\f1;\f3Min utime[s]\f1;\f3Max utime[s]\f1
_
.TH
;;;;
<?php foreach($data['utime'] as $l): ?>
\&\x'4p'<?=$l['name'] ?>;<?=$l['pid'] ?>;<?=$l['avg_utime'] ?>;<?=$l['min_utime'] ?>;<?=$l['max_utime'] ?>

<?php endforeach; ?>
.TE
