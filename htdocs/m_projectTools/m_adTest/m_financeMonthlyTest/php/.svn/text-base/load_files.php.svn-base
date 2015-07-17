<?php

$filename1 = $_GET["filename1"];
$filename2 = $_GET["filename2"];

$file1 = "../html_data/" . $filename1;
$file2 = "../html_data/" . $filename2;

$retArray = Array(
    "table_first" => file_get_contents($file1),
    "table_last" => file_get_contents($file2),
);

echo json_encode($retArray);
