<?php

$prodName = $_POST["prodName"];

include("dbUtil.php");
$versions = dbUtil(LoadVersions, array($prodName));
sort($versions);
foreach (array_reverse($versions) as $version) {
    echo "<option>$version</option>", "\n";
}
