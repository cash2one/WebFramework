<?php

$detail_str = $_POST["detail_str"];
file_put_contents("abc.txt", $detail_str);

$cmd = "cd ../deploy; ./run.sh click '$detail_str'";
exec($cmd, $lines);
$click_url = $lines[0];
echo "<a target='_clickUrl' href='$click_url'>$click_url</a>";
