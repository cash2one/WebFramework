<?php

$tempArray = Array();

$lines = file("svn.history");
foreach (array_reverse($lines) as $line) {
    $line = trim($line);
    list($user, $url) = explode("", $line);
    array_push($tempArray, "<tr><td>$user</td><td><a href=''>$url</a></td></tr>");
}

echo implode("\n", $tempArray);
