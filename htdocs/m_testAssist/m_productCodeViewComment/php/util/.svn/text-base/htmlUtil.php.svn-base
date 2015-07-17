<style>
    th {text-align: right}
</style>

<?php
date_default_timezone_set("PRC");

include(dirname(__FILE__) . "/dbUtil.php");

function html_listCreators() {
    $creatorList = dbUtil(GetCreatorList);
    foreach ($creatorList as $creator) {
        echo "<option>$creator</option>", "\n";
    }
}

function html_listProducts() {
    $prodList = dbUtil(GetProdList);
    foreach ($prodList as $prod) {
        echo "<option>$prod</option>", "\n";
    }
}

function html_listVersions($prodName, $showAll = false) {
    $versionList = dbUtil(GetVersionList, $prodName);
    if ($showAll == true)
        echo "<option value='*'>所有版本</option>", "\n";
    foreach ($versionList as $version) {
        echo "<option>$version</option>", "\n";
    }
}

function html_codeInfo($prodName, $version) {
    $code_info = dbUtil(GetCodeInfo, $prodName, $version);
    echo "<table style='color:#FFFFE0'>", "\n";
    echo "<tr><th></th><td><b>== $prodName@$version ==</b></td></tr>", "\n";
    echo "<tr><th>Svn地址: </th><td>" . $code_info["codeSvnPath"] . "</td></tr>";
    echo "<tr><th>创建者: </th><td>" . $code_info["creator"] . "</td></tr>";
    echo "<tr><th>创建时间: </th><td>" . date("Y-m-d H:i:s", $code_info["time"]) . "</td></tr>";
    echo "<tr><th>存储路径: </th><td>" . $code_info["diskPath"] . "</td></tr>";
    echo "</table>", "\n";
}

function html_prodWithoutTable() {
    $productList = dbUtil(GetProductList);
    $prodMapArray = dbUtil(GetProdNameMap);
    echo "<table>", "\n";
    echo "<tr><th style='text-align:center'>服务名</th><th style='text-align:center'>集合名(表名)</th></tr>", "\n";
    foreach ($productList as $product) {
        if (array_key_exists($product, $prodMapArray)) {
            echo "<tr><td>$product:</td><td>" . $prodMapArray[$product] . "</tr>", "\n";

        } else {
            echo "<tr><td>$product:</td><td><input type='text' name='$product' placeholder='设置mongo集合名' /></td></tr>", "\n";
        }
    }
    echo "</table>";
}

function html_listCommentors($prodName) {
    $commentorList = dbUtil(GetCommentorList, $prodName);
    foreach ($commentorList as $commentor) {
        echo "<option>$commentor</option>", "\n";
    }
}
