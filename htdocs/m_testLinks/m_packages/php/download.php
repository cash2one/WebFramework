<?php
$file = urldecode($_GET["file_path"]);
header("Content-Type:application/force-download");
header("Content-Disposition:attachment; filename=" . basename($file));
readfile($file);
?>
