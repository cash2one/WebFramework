<?php

$lines = file("../logs/group.txt");
sort($lines);
echo "[" . implode(",", $lines) . "]";
