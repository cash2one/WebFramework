<?php

$file = $_POST["logpath"];
$line_begin = $_POST["linenum"];
$host = $_POST["host"];

$cmd = "wc -l $file";
if($host!=null){
    $cmd = "ssh $host "."\"".$cmd."\"";
}

$lineno_of_file = exec($cmd);
list($line_end, $file) = explode(" ", $lineno_of_file);
$output = array();
if($line_begin < $line_end) {
	$line_begin = $line_begin + 1;
    $cmd = "sed -n '$line_begin, $line_end'p $file";
    if($host!=null)
    	$cmd = "ssh $host "."\"".$cmd."\"";
	exec($cmd, $output);
}

$results["linenum"] = $line_end;
$results["lines"] = $output;

echo json_encode($results);

?>
