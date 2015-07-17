<?php
include("dbUtil.php");
$whitelistNames = dbUtil(GetWhitelistNames, null);
sort($whitelistNames);
foreach (array_reverse($whitelistNames) as $name) {
    echo "<option>$name</option>", "\n";
}
?>
