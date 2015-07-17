<?php

$whitelistName = $_POST["whitelistName"];
#$whitelistName = "union_stderr_whitelist";

include("dbUtil.php");
$contents = dbUtil(GetWhitelistContents, array($whitelistName));
sort($contents);
foreach (array_reverse($contents) as $content) {
    echo $content, "\n";
}

?>
