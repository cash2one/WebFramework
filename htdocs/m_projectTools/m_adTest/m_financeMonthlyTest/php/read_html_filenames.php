<?php
    
$html_dir = "../html_data/";
$files = glob("$html_dir/case_*.html");
$filenames = Array();
foreach ($files as $file) {
    $filename = substr(basename($file), 16, 32);
    $state_name = file_get_contents("./status-list/$filename");
    array_push($filenames, Array($state_name, basename($file)));
}

# sort by key
ksort($filenames);

$retObj = Array(
    "filenames" => $filenames,
);
echo json_encode($retObj);
