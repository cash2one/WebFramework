<?php

$file = $_POST["logpath"];
$line_begin = $_POST["linenum"];

$lineno_of_file = exec("wc -l $file");
list($line_end, $file) = split(" ", $lineno_of_file);

$output = array();
if($line_begin < $line_end) {
	$line_begin = $line_begin + 1;
    $cmd = "sed -n '$line_begin, $line_end'p $file";
	exec("$cmd", $output);
}

$results["linenum"] = $line_end;
$results["lines"] = $output;

echo json_encode($results);

?>
